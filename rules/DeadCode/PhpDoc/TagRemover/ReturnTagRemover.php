<?php

declare(strict_types=1);

namespace Rector\DeadCode\PhpDoc\TagRemover;

use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\DeadCode\PhpDoc\DeadReturnTagValueNodeAnalyzer;

final readonly class ReturnTagRemover
{
    public function __construct(
        private DeadReturnTagValueNodeAnalyzer $deadReturnTagValueNodeAnalyzer
    ) {
    }

    public function removeReturnTagIfUseless(PhpDocInfo $phpDocInfo, ClassMethod|Function_ $functionLike): bool
    {
        // remove existing type
        $returnTagValueNode = $phpDocInfo->getReturnTagValue();
        if (! $returnTagValueNode instanceof ReturnTagValueNode) {
            return false;
        }

        $isReturnTagValueDead = $this->deadReturnTagValueNodeAnalyzer->isDead($returnTagValueNode, $functionLike);
        if (! $isReturnTagValueDead) {
            return false;
        }

        $phpDocInfo->removeByType(ReturnTagValueNode::class);
        return true;
    }
}
