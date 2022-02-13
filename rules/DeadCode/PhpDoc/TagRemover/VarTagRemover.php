<?php

declare(strict_types=1);

namespace Rector\DeadCode\PhpDoc\TagRemover;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Property;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Generic\TemplateObjectWithoutClassType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\ValueObject\Type\BracketsAwareUnionTypeNode;
use Rector\BetterPhpDocParser\ValueObject\Type\SpacingAwareArrayTypeNode;
use Rector\DeadCode\PhpDoc\DeadVarTagValueNodeAnalyzer;
use Rector\PHPStanStaticTypeMapper\DoctrineTypeAnalyzer;

final class VarTagRemover
{
    public function __construct(
        private readonly DoctrineTypeAnalyzer $doctrineTypeAnalyzer,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly DeadVarTagValueNodeAnalyzer $deadVarTagValueNodeAnalyzer
    ) {
    }

    public function removeVarTagIfUseless(PhpDocInfo $phpDocInfo, Property $property): void
    {
        $varTagValueNode = $phpDocInfo->getVarTagValueNode();
        if (! $varTagValueNode instanceof VarTagValueNode) {
            return;
        }

        $isVarTagValueDead = $this->deadVarTagValueNodeAnalyzer->isDead($varTagValueNode, $property);
        if (! $isVarTagValueDead) {
            return;
        }

        $phpDocInfo->removeByType(VarTagValueNode::class);
    }

    public function removeVarPhpTagValueNodeIfNotComment(Expression | Property | Param $node, Type $type): void
    {
        if ($type instanceof TemplateObjectWithoutClassType) {
            return;
        }

        // keep doctrine collection narrow type
        if ($this->doctrineTypeAnalyzer->isDoctrineCollectionWithIterableUnionType($type)) {
            return;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        $varTagValueNode = $phpDocInfo->getVarTagValueNode();
        if (! $varTagValueNode instanceof VarTagValueNode) {
            return;
        }

        // has description? keep it
        if ($varTagValueNode->description !== '') {
            return;
        }

        // keep generic types
        if ($varTagValueNode->type instanceof GenericTypeNode) {
            return;
        }

        // keep string[] etc.
        if ($this->isNonBasicArrayType($varTagValueNode)) {
            return;
        }

        $phpDocInfo->removeByType(VarTagValueNode::class);
    }

    private function isNonBasicArrayType(VarTagValueNode $varTagValueNode): bool
    {
        if ($varTagValueNode->type instanceof BracketsAwareUnionTypeNode) {
            foreach ($varTagValueNode->type->types as $type) {
                if ($this->isArrayTypeNode($type)) {
                    return true;
                }

                // keep generic types
                if ($type instanceof GenericTypeNode) {
                    return true;
                }
            }
        }

        if (! $this->isArrayTypeNode($varTagValueNode->type)) {
            return false;
        }

        return (string) $varTagValueNode->type !== 'array';
    }

    private function isArrayTypeNode(TypeNode $typeNode): bool
    {
        return in_array($typeNode::class, [SpacingAwareArrayTypeNode::class, ArrayShapeNode::class], true);
    }
}
