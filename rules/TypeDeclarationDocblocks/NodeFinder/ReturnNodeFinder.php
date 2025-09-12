<?php

declare(strict_types=1);

namespace Rector\TypeDeclarationDocblocks\NodeFinder;

use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\TypeDeclaration\NodeAnalyzer\ReturnAnalyzer;

final readonly class ReturnNodeFinder
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private ReturnAnalyzer $returnAnalyzer,
    ) {
    }

    public function findOnlyReturnWithExpr(ClassMethod|Function_ $functionLike): ?Return_
    {
        $returnsScoped = $this->betterNodeFinder->findReturnsScoped($functionLike);
        if (! $this->returnAnalyzer->hasOnlyReturnWithExpr($functionLike, $returnsScoped)) {
            return null;
        }

        if (count($returnsScoped) !== 1) {
            return null;
        }

        return $returnsScoped[0];
    }
}
