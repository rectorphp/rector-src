<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Include_;
use PhpParser\Node\Expr\Variable;
use Rector\Core\NodeAnalyzer\CompactFuncCallAnalyzer;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;

final class ExprUsedInNextStmtAnalyzer
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private NodeComparator $nodeComparator,
        private CompactFuncCallAnalyzer $compactFuncCallAnalyzer
    )
    {
    }

    public function isUsed(Expr $expr): bool
    {
        return (bool) $this->betterNodeFinder->findFirstNext($expr, function (Node $node) use ($expr): bool {
            if (! $node instanceof FuncCall) {
                if ($this->nodeComparator->areNodesEqual($expr, $node)) {
                    return true;
                }

                return $node instanceof Include_;
            }

            if ($expr instanceof Variable) {
                return $this->compactFuncCallAnalyzer->isInCompact($node, $expr);
            }

            return false;
        });
    }
}
