<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PhpDocNodeVisitor;

use PhpParser\Node as PhpParserNode;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Namespace_;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\ValueObject\PhpDocAttributeKey;
use Rector\Core\Configuration\CurrentNodeProvider;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Naming\Naming\UseImportsResolver;
use Rector\NodeTypeResolver\ValueObject\OldToNewType;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\StaticTypeMapper\ValueObject\Type\ShortenedObjectType;
use Symplify\Astral\PhpDocParser\PhpDocNodeVisitor\AbstractPhpDocNodeVisitor;

final class ClassRenamePhpDocNodeVisitor extends AbstractPhpDocNodeVisitor
{
    /**
     * @var OldToNewType[]
     */
    private array $oldToNewTypes = [];

    public function __construct(
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly CurrentNodeProvider $currentNodeProvider,
        private readonly UseImportsResolver $useImportsResolver,
        private readonly BetterNodeFinder $betterNodeFinder
    ) {
    }

    public function beforeTraverse(Node $node): void
    {
        if ($this->oldToNewTypes === []) {
            throw new ShouldNotHappenException('Configure "$oldToNewClasses" first');
        }
    }

    public function enterNode(Node $node): ?Node
    {
        if (! $node instanceof IdentifierTypeNode) {
            return null;
        }

        $phpParserNode = $this->currentNodeProvider->getNode();
        if (! $phpParserNode instanceof \PhpParser\Node) {
            throw new ShouldNotHappenException();
        }

        $identifier = clone $node;

        $namespacedName = $this->resolveNamespacedName($phpParserNode, $identifier->name);
        $identifier->name = $namespacedName;
        $staticType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($identifier, $phpParserNode);

        // make sure to compare FQNs
        if ($staticType instanceof ShortenedObjectType) {
            $staticType = new ObjectType($staticType->getFullyQualifiedName());
        }

        foreach ($this->oldToNewTypes as $oldToNewType) {
            if (! $staticType->equals($oldToNewType->getOldType())) {
                continue;
            }

            $newTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode(
                $oldToNewType->getNewType(),
                TypeKind::ANY
            );

            $parentType = $node->getAttribute(PhpDocAttributeKey::PARENT);
            if ($parentType instanceof TypeNode) {
                // mirror attributes
                $newTypeNode->setAttribute(PhpDocAttributeKey::PARENT, $parentType);
            }

            return $newTypeNode;
        }

        return null;
    }

    /**
     * @param OldToNewType[] $oldToNewTypes
     */
    public function setOldToNewTypes(array $oldToNewTypes): void
    {
        $this->oldToNewTypes = $oldToNewTypes;
    }

    private function resolveNamespacedName(PhpParserNode $phpParserNode, string $name): string
    {
        if (str_starts_with($name, '\\')) {
            return $name;
        }

        $namespace = $this->betterNodeFinder->findParentType($phpParserNode, Namespace_::class);
        $namespacedName = $namespace instanceof Namespace_
            ? $namespace->name->toString() . '\\' . $name
            : $name;

        $uses = $this->useImportsResolver->resolveForNode($phpParserNode);

        foreach ($uses as $use) {
            $prefix = $use instanceof GroupUse
                ? $use->prefix . '\\'
                : '';

            foreach ($use->uses as $useUse) {
                if ($useUse->alias instanceof Identifier) {
                    continue;
                }

                $useUseName = $prefix . $useUse->name->toString();
                if ($useUseName === $namespacedName) {
                    return $name;
                }

                if ($useUse->name->getLast() === $namespacedName) {
                    return $useUseName;
                }
            }
        }

        dump($namespacedName === $name);

        return $name;
    }
}
