<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PhpDocNodeVisitor;

use PhpParser\Node as PhpNode;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Generic\TemplateObjectType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\ValueObject\PhpDocAttributeKey;
use Rector\Exception\ShouldNotHappenException;
use Rector\NodeTypeResolver\ValueObject\OldToNewType;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeVisitor\AbstractPhpDocNodeVisitor;
use Rector\Renaming\Collector\RenamedNameCollector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\StaticTypeMapper\ValueObject\Type\AliasedObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\NonExistingObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\ShortenedObjectType;

final class ClassRenamePhpDocNodeVisitor extends AbstractPhpDocNodeVisitor
{
    /**
     * @var OldToNewType[]
     */
    private array $oldToNewTypes = [];

    private bool $hasChanged = false;

    private ?PhpNode $currentPhpNode = null;

    public function __construct(
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly RenamedNameCollector $renamedNameCollector
    ) {
    }

    public function setCurrentPhpNode(PhpNode $phpNode): void
    {
        $this->currentPhpNode = $phpNode;
    }

    public function beforeTraverse(Node $node): void
    {
        if ($this->oldToNewTypes === []) {
            throw new ShouldNotHappenException('Configure "$oldToNewClasses" first');
        }

        if (! $this->currentPhpNode instanceof PhpNode) {
            throw new ShouldNotHappenException('Configure "$currentPhpNode" first');
        }

        $this->hasChanged = false;
    }

    public function enterNode(Node $node): ?Node
    {
        if (! $node instanceof IdentifierTypeNode) {
            return null;
        }

        /** @var \PhpParser\Node $currentPhpNode */
        $currentPhpNode = $this->currentPhpNode;

        $identifier = clone $node;
        $identifierName = $identifier->name;
        $identifier->name = $this->resolveNamespacedName($identifier, $currentPhpNode, $node->name);
        $staticType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($identifier, $currentPhpNode);

        // make sure to compare FQNs
        $objectType = $this->ensureFQCNObject($staticType, $identifierName);

        foreach ($this->oldToNewTypes as $oldToNewType) {
            /** @var ObjectType $oldType */
            $oldType = $oldToNewType->getOldType();
            if (! $objectType->equals($oldType)) {
                continue;
            }

            $newTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($oldToNewType->getNewType());

            $parentType = $node->getAttribute(PhpDocAttributeKey::PARENT);
            if ($parentType instanceof TypeNode) {
                // mirror attributes
                $newTypeNode->setAttribute(PhpDocAttributeKey::PARENT, $parentType);
            }

            $this->hasChanged = true;

            $this->renamedNameCollector->add($oldType->getClassName());
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

    public function hasChanged(): bool
    {
        return $this->hasChanged;
    }

    private function resolveNamespacedName(
        IdentifierTypeNode $identifierTypeNode,
        PhpNode $phpNode,
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
            $phpNode
        );

        // @template and non existing object type from docblock not to be renamed
        // it not exist on use, and not exists in existing namespace
        // and not start with \, so it can be anything
        if ($staticType instanceof TemplateObjectType || $staticType instanceof NonExistingObjectType) {
            return '';
        }

        return $name;
    }

    private function ensureFQCNObject(Type $type, string $identiferName): ObjectType|Type
    {
        if ($type instanceof ShortenedObjectType && str_starts_with($identiferName, '\\')) {
            return new ObjectType(ltrim($identiferName, '\\'));
        }

        if ($type instanceof ShortenedObjectType || $type instanceof AliasedObjectType) {
            return new ObjectType($type->getFullyQualifiedName());
        }

        return $type;
    }
}
