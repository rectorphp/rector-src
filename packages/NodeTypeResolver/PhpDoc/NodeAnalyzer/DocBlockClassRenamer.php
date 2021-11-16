<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PhpDoc\NodeAnalyzer;

use PhpParser\Node\Name;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PropertyTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\ValueObject\Type\BracketsAwareUnionTypeNode;
use Rector\BetterPhpDocParser\ValueObject\Type\SpacingAwareArrayTypeNode;
use Rector\NodeTypeResolver\PhpDoc\PhpDocNodeTraverser\RenamingPhpDocNodeVisitorFactory;
use Rector\NodeTypeResolver\PhpDocNodeVisitor\ClassRenamePhpDocNodeVisitor;
use Rector\NodeTypeResolver\ValueObject\OldToNewType;

final class DocBlockClassRenamer
{
    public function __construct(
        private ClassRenamePhpDocNodeVisitor $classRenamePhpDocNodeVisitor,
        private RenamingPhpDocNodeVisitorFactory $renamingPhpDocNodeVisitorFactory
    ) {
    }

    /**
     * @param OldToNewType[] $oldToNewTypes
     */
    public function renamePhpDocType(PhpDocInfo $phpDocInfo, array $oldToNewTypes): void
    {
        if ($oldToNewTypes === []) {
            return;
        }

        $phpDocNode = $phpDocInfo->getPhpDocNode();
        $tags = $phpDocNode->getTags();

        foreach ($tags as $tag) {
            $tagValueNode = $tag->value;
            $tagName = $phpDocInfo->resolveNameForPhpDocTagValueNode($tagValueNode);

            // MethodTagValueNode doesn't has type property
            if ($tagValueNode instanceof MethodTagValueNode) {
                return;
            }

            if (! is_string($tagName)) {
                continue;
            }

            /**
             * @var ReturnTagValueNode|ParamTagValueNode|VarTagValueNode|PropertyTagValueNode $tagValueNode
             */
            if ($tagValueNode->type instanceof BracketsAwareUnionTypeNode && $this->hasSpecialClassNameInUnion(
                $tagValueNode->type
            )) {
                return;
            }

            if ($tagValueNode->type instanceof NullableTypeNode && $this->hasSpecialClassName(
                $tagValueNode->type->type
            )) {
                return;
            }

            if ($this->hasSpecialClassName($tagValueNode->type)) {
                return;
            }
        }

        $phpDocNodeTraverser = $this->renamingPhpDocNodeVisitorFactory->create();
        $this->classRenamePhpDocNodeVisitor->setOldToNewTypes($oldToNewTypes);

        $phpDocNodeTraverser->traverse($phpDocInfo->getPhpDocNode());
    }

    private function hasSpecialClassNameInUnion(BracketsAwareUnionTypeNode $bracketsAwareUnionTypeNode): bool
    {
        foreach ($bracketsAwareUnionTypeNode->types as $type) {
            if ($this->hasSpecialClassName($type)) {
                return true;
            }
        }

        return false;
    }

    private function hasSpecialClassName(TypeNode $typeNode): bool
    {
        if ($typeNode instanceof SpacingAwareArrayTypeNode) {
            $typeNode = $typeNode->type;
        }

        if ($typeNode instanceof GenericTypeNode) {
            foreach ($typeNode->genericTypes as $type) {
                if ($this->hasSpecialClassName($type)) {
                    return true;
                }
            }
        }

        if (! $typeNode instanceof IdentifierTypeNode) {
            return false;
        }

        $name = new Name((string) $typeNode);
        return $name->isSpecialClassName();
    }
}
