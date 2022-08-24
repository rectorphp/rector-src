<?php

declare(strict_types=1);

namespace Rector\EarlyReturn\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;

final class IfAndAnalyzer
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly NodeComparator $nodeComparator,
    ) {
    }

    public function isIfAndWithInstanceof(BooleanAnd $booleanAnd): bool
    {
        if (! $booleanAnd->left instanceof Instanceof_) {
            return false;
        }

        // only one instanceof check
        return ! $booleanAnd->right instanceof Instanceof_;
    }

    public function isIfStmtExprUsedInNextReturn(If_ $if, Return_ $return): bool
    {
        if (! $return->expr instanceof Expr) {
            return false;
        }

        $ifExprs = $this->betterNodeFinder->findInstanceOf($if->stmts, Expr::class);
        foreach ($ifExprs as $ifExpr) {
            $isExprFoundInReturn = (bool) $this->betterNodeFinder->findFirst(
                $return->expr,
                fn (Node $node): bool => $this->nodeComparator->areNodesEqual($node, $ifExpr)
            );
            if ($isExprFoundInReturn) {
                return true;
            }
        }

        return false;
    }
}
