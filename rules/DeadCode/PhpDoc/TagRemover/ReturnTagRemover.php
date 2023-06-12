<?php

declare(strict_types=1);

namespace Rector\DeadCode\PhpDoc\TagRemover;

use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\DeadCode\PhpDoc\DeadReturnTagValueNodeAnalyzer;

final class ReturnTagRemover
{
    public function __construct(
        private readonly DeadReturnTagValueNodeAnalyzer $deadReturnTagValueNodeAnalyzer
    ) {
    }

    public function removeReturnTagIfUseless(PhpDocInfo $phpDocInfo, ClassLike $classLike, ClassMethod $classMethod): bool
    {
        // remove existing type
        $returnTagValueNode = $phpDocInfo->getReturnTagValue();
        if (! $returnTagValueNode instanceof ReturnTagValueNode) {
            return false;
        }

        $isReturnTagValueDead = $this->deadReturnTagValueNodeAnalyzer->isDead($returnTagValueNode, $classLike, $classMethod);
        if (! $isReturnTagValueDead) {
            return false;
        }

        $phpDocInfo->removeByType(ReturnTagValueNode::class);
        return true;
    }
}
