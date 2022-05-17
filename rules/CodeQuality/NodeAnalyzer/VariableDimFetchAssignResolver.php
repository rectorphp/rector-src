<?php

declare(strict_types=1);

namespace Rector\CodeQuality\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use Rector\CodeQuality\ValueObject\KeyAndExpr;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;

final class VariableDimFetchAssignResolver
{
    public function __construct(
        private readonly NodeComparator $nodeComparator,
        private readonly BetterNodeFinder $betterNodeFinder
    ) {
    }

    /**
     * @param Stmt[] $stmts
     * @return KeyAndExpr[]
     */
    public function resolveFromStmtsAndVariable(array $stmts, Variable $variable): array
    {
        $keysAndExprs = [];

        foreach ($stmts as $stmt) {
            if (! $stmt instanceof Expression) {
                return [];
            }

            $stmtExpr = $stmt->expr;
            if (! $stmtExpr instanceof Assign) {
                return [];
            }

            $assign = $stmtExpr;

            $keyExpr = $this->matchKeyOnArrayDimFetchOfVariable($assign, $variable);
            if (! $keyExpr instanceof Expr) {
                return [];
            }

            $keysAndExprs[] = new KeyAndExpr($keyExpr, $assign->expr);
        }

        return $keysAndExprs;
    }

    private function matchKeyOnArrayDimFetchOfVariable(Assign $assign, Variable $variable): ?Expr
    {
        if (! $assign->var instanceof ArrayDimFetch) {
            return null;
        }

        $arrayDimFetch = $assign->var;
        if (! $this->nodeComparator->areNodesEqual($arrayDimFetch->var, $variable)) {
            return null;
        }

        $isFoundInExpr = (bool) $this->betterNodeFinder->findFirst(
            $assign->expr,
            fn (Node $subNode): bool => $this->nodeComparator->areNodesEqual($subNode, $variable)
        );

        if ($isFoundInExpr) {
            return null;
        }

        return $arrayDimFetch->dim;
    }
}
