<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Variable;
use Rector\NodeAnalyzer\ExprAnalyzer;
use Rector\PhpParser\Node\BetterNodeFinder;

final readonly class SafeLeftTypeBooleanAndOrAnalyzer
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly ExprAnalyzer $exprAnalyzer
    ) {
    }

    public function isSafe(BooleanAnd|BooleanOr $booleanAnd): bool
    {
        $hasNonTypedFromParam = (bool) $this->betterNodeFinder->findFirst(
            $booleanAnd->left,
            fn (Node $node): bool => $node instanceof Variable && $this->exprAnalyzer->isNonTypedFromParam($node)
        );

        if ($hasNonTypedFromParam) {
            return false;
        }

        // get type from Property and ArrayDimFetch is unreliable
        return ! (bool) $this->betterNodeFinder->findFirst(
            $booleanAnd->left,
            static fn (Node $node): bool => $node instanceof PropertyFetch || $node instanceof StaticPropertyFetch || $node instanceof ArrayDimFetch
        );
    }
}
