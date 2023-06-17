<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PhpDocNodeVisitor;

use PhpParser\Node as PhpParserNode;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Use_;
use PHPStan\Analyser\Scope;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\ValueObject\PhpDocAttributeKey;
use Rector\Core\Configuration\CurrentNodeProvider;
use Rector\Core\Configuration\RectorConfigProvider;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Naming\Naming\UseImportsResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\ValueObject\OldToNewType;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeVisitor\AbstractPhpDocNodeVisitor;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\StaticTypeMapper\ValueObject\Type\ShortenedObjectType;

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
        private readonly RectorConfigProvider $rectorConfigProvider
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
        if (! $phpParserNode instanceof PhpParserNode) {
            throw new ShouldNotHappenException();
        }

        $virtualNode = $phpParserNode->getAttribute(AttributeKey::VIRTUAL_NODE);
        if ($virtualNode === true) {
            return null;
        }

        $identifier = clone $node;
        $identifier->name = $this->resolveNamespacedName($identifier, $phpParserNode, $node->name);
        $staticType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($identifier, $phpParserNode);

        $shouldImport = $this->rectorConfigProvider->shouldImportNames();
        $isNoNamespacedName = ! str_starts_with($identifier->name, '\\') && substr_count($identifier->name, '\\') === 0;

        // tweak overlapped import + rename
        if ($shouldImport && $isNoNamespacedName) {
            return null;
        }

        // make sure to compare FQNs
        $objectType = $this->expandShortenedObjectType($staticType);
        foreach ($this->oldToNewTypes as $oldToNewType) {
            if (! $objectType->equals($oldToNewType->getOldType())) {
                continue;
            }

            $newTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($oldToNewType->getNewType());

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

    private function resolveNamespacedName(
        IdentifierTypeNode $identifierTypeNode,
        PhpParserNode $phpParserNode,
        string $name
    ): string {
        if (str_starts_with($name, '\\')) {
            return $name;
        }

        if (str_contains($name, '\\')) {
            return $name;
        }

        $staticType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType(
            $identifierTypeNode,
            $phpParserNode
        );

        if (! $staticType instanceof ObjectType) {
            return $name;
        }

        if ($staticType instanceof ShortenedObjectType) {
            return $name;
        }

        $uses = $this->useImportsResolver->resolveForNode($phpParserNode);
        $scope = $phpParserNode->getAttribute(AttributeKey::SCOPE);

        if (! $scope instanceof Scope) {
            if (! $phpParserNode->hasAttribute(AttributeKey::ORIGINAL_NODE)) {
                return $this->resolveNamefromUse($uses, $name);
            }

            return '';
        }

        $namespaceName = $scope->getNamespace();
        if ($namespaceName === null) {
            return $this->resolveNamefromUse($uses, $name);
        }

        if ($uses === []) {
            return $namespaceName . '\\' . $name;
        }

        $nameFromUse = $this->resolveNamefromUse($uses, $name);

        if ($nameFromUse !== $name) {
            return $nameFromUse;
        }

        return $namespaceName . '\\' . $nameFromUse;
    }

    /**
     * @param Use_[]|GroupUse[] $uses
     */
    private function resolveNamefromUse(array $uses, string $name): string
    {
        foreach ($uses as $use) {
            $prefix = $this->useImportsResolver->resolvePrefix($use);

            foreach ($use->uses as $useUse) {
                if ($useUse->alias instanceof Identifier) {
                    continue;
                }

                $lastName = $useUse->name->getLast();
                if ($lastName === $name) {
                    return $prefix . $useUse->name->toString();
                }
            }
        }

        return $name;
    }

    private function expandShortenedObjectType(Type $type): ObjectType|Type
    {
        if ($type instanceof ShortenedObjectType) {
            return new ObjectType($type->getFullyQualifiedName());
        }

        return $type;
    }
}
