<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeAnalyzer\ReturnTypeAnalyzer;

use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Expr\YieldFrom;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\TypeDeclaration\NodeAnalyzer\ReturnAnalyzer;

final readonly class AlwaysStrictReturnAnalyzer
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private ReturnAnalyzer $returnAnalyzer
    ) {
    }

    /**
     * @return Return_[]
     */
    public function matchAlwaysStrictReturns(ClassMethod|Closure|Function_ $functionLike): array
    {
        if ($functionLike->stmts === null) {
            return [];
        }

        if ($this->betterNodeFinder->hasInstancesOfInFunctionLikeScoped(
            $functionLike,
            [Yield_::class, YieldFrom::class]
        )) {
            return [];
        }

        /** @var Return_[] $returns */
        $returns = $this->betterNodeFinder->findInstancesOfInFunctionLikeScoped($functionLike, Return_::class);
        if ($returns === []) {
            return [];
        }

        if (! $this->returnAnalyzer->hasOnlyReturnWithExpr($functionLike)) {
            return [];
        }

        return $returns;
    }
}
