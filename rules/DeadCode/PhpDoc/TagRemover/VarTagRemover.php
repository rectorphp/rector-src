<?php

declare(strict_types=1);

namespace Rector\DeadCode\PhpDoc\TagRemover;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Property;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\Type\Generic\TemplateObjectWithoutClassType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\DeadCode\PhpDoc\DeadVarTagValueNodeAnalyzer;
use Rector\PHPStanStaticTypeMapper\DoctrineTypeAnalyzer;

final class VarTagRemover
{
    public function __construct(
        private readonly DoctrineTypeAnalyzer $doctrineTypeAnalyzer,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly DeadVarTagValueNodeAnalyzer $deadVarTagValueNodeAnalyzer,
        private readonly PhpDocTypeChanger $phpDocTypeChanger,
        private readonly DocBlockUpdater $docBlockUpdater,
    ) {
    }

    public function removeVarTagIfUseless(PhpDocInfo $phpDocInfo, Property $property): bool
    {
        $varTagValueNode = $phpDocInfo->getVarTagValueNode();
        if (! $varTagValueNode instanceof VarTagValueNode) {
            return false;
        }

        $isVarTagValueDead = $this->deadVarTagValueNodeAnalyzer->isDead($varTagValueNode, $property);
        if (! $isVarTagValueDead) {
            return false;
        }

        if ($this->phpDocTypeChanger->isAllowed($varTagValueNode->type)) {
            return false;
        }

        $hasChanged = $phpDocInfo->removeByType(VarTagValueNode::class);
        if ($hasChanged === false) {
            return false;
        }

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($property);
        return true;
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

        // keep string[] etc.
        if ($this->phpDocTypeChanger->isAllowed($varTagValueNode->type)) {
            return;
        }

        $hasChanged = $phpDocInfo->removeByType(VarTagValueNode::class);
        if (! $hasChanged) {
            return;
        }

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);
    }
}
