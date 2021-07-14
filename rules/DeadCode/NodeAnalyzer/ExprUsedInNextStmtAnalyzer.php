<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr;
use Rector\Core\PhpParser\Node\BetterNodeFinder;

final class ExprUsedInNextStmtAnalyzer
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private ExprUsedInNodeAnalyzer $exprUsedInNodeAnalyzer
    )
    {
    }

    public function isUsed(Expr $expr): bool
    {
        return (bool) $this->betterNodeFinder->findFirstNext($expr, function (Node $node) use ($expr): bool {
            return $this->exprUsedInNodeAnalyzer->isUsed($node, $expr);
        });
    }
}
