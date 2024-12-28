<?php

declare(strict_types=1);

namespace Rector\DeadCode\PhpDoc\TagRemover;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassConst;
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
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\PHPStanStaticTypeMapper\DoctrineTypeAnalyzer;

final readonly class VarTagRemover
{
    public function __construct(
        private DoctrineTypeAnalyzer $doctrineTypeAnalyzer,
        private PhpDocInfoFactory $phpDocInfoFactory,
        private DeadVarTagValueNodeAnalyzer $deadVarTagValueNodeAnalyzer,
        private PhpDocTypeChanger $phpDocTypeChanger,
        private DocBlockUpdater $docBlockUpdater,
        private TypeComparator $typeComparator,
    ) {
    }

    public function removeVarTagIfUseless(PhpDocInfo $phpDocInfo, Property|ClassConst $property): bool
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

        $phpDocInfo->removeByType(VarTagValueNode::class);
        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($property);

        return true;
    }

    /**
     * @api generic
     */
    public function removeVarTag(Node $node): bool
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        $varTagValueNode = $phpDocInfo->getVarTagValueNode();
        if (! $varTagValueNode instanceof VarTagValueNode) {
            return false;
        }

        $phpDocInfo->removeByType(VarTagValueNode::class);
        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

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

        // keep subtypes like positive-int
        if ($this->shouldKeepSubtypes($type, $phpDocInfo->getVarType())) {
            return;
        }

        $phpDocInfo->removeByType(VarTagValueNode::class);
        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);
    }

    private function shouldKeepSubtypes(Type $type, Type $varType): bool
    {
        return ! $this->typeComparator->areTypesEqual($type, $varType)
            && $this->typeComparator->isSubtype($varType, $type);
    }
}
