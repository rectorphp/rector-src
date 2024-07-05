<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeAnalyzer\ReturnTypeAnalyzer;

use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Expr\YieldFrom;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\TypeDeclaration\NodeAnalyzer\ReturnFilter\ExclusiveNativeCallLikeReturnMatcher;

final readonly class StrictNativeFunctionReturnTypeAnalyzer
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private ExclusiveNativeCallLikeReturnMatcher $exclusiveNativeCallLikeReturnMatcher,
    ) {
    }

    /**
     * @return CallLike[]|null
     */
    public function matchAlwaysReturnNativeCallLikes(ClassMethod|Function_ $functionLike): ?array
    {
        if ($functionLike->stmts === null) {
            return null;
        }

        if ($this->betterNodeFinder->hasInstancesOfInFunctionLikeScoped(
            $functionLike,
            [Yield_::class, YieldFrom::class]
        )) {
            return null;
        }

        $returns = $this->betterNodeFinder->findReturnsScoped($functionLike);
        if ($returns === []) {
            return null;
        }

        return $this->exclusiveNativeCallLikeReturnMatcher->match($returns);
    }
}
