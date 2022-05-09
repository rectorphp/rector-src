<?php

declare(strict_types=1);

namespace Rector\Php74\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ClosureUse;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\Core\PhpParser\Node\BetterNodeFinder;

final class ClosureArrowFunctionAnalyzer
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly NodeComparator $nodeComparator
    ) {
    }

    public function matchArrowFunctionExpr(Closure $closure): ?Expr
    {
        if (count($closure->stmts) !== 1) {
            return null;
        }

        $onlyStmt = $closure->stmts[0];
        if (! $onlyStmt instanceof Return_) {
            return null;
        }

        /** @var Return_ $return */
        $return = $onlyStmt;
        if ($return->expr === null) {
            return null;
        }

        if ($this->shouldSkipForUsedReferencedValue($closure)) {
            return null;
        }

        return $return->expr;
    }

    public function matchArrowFunctionExprIfElse(Closure $closure): ?Expr
    {
        if (count($closure->stmts) !== 2) {
            return null;
        }

        if (! $closure->stmts[0] instanceof If_) {
            return null;
        }

        if ($closure->stmts[0]->stmts === []) {
            return null;
        }

        if (! $closure->stmts[0]->stmts[0] instanceof Return_) {
            return null;
        }

        if (! $closure->stmts[0]->stmts[0]->expr instanceof Expr) {
            return null;
        }

        if (! $closure->stmts[1] instanceof Return_) {
            return null;
        }

        if (! $closure->stmts[1]->expr instanceof Expr) {
            return null;
        }

        return new Ternary($closure->stmts[0]->cond, $closure->stmts[0]->stmts[0]->expr, $closure->stmts[1]->expr);
    }

    private function shouldSkipForUsedReferencedValue(Closure $closure): bool
    {
        $referencedValues = $this->resolveReferencedUseVariablesFromClosure($closure);
        if ($referencedValues === []) {
            return false;
        }

        $isFoundInStmt = (bool) $this->betterNodeFinder->findFirstInFunctionLikeScoped($closure, function (Node $node) use (
            $referencedValues
        ): bool {
            foreach ($referencedValues as $referencedValue) {
                if ($this->nodeComparator->areNodesEqual($node, $referencedValue)) {
                    return true;
                }
            }

            return false;
        });

        if ($isFoundInStmt) {
            return true;
        }

        return $this->isFoundInInnerUses($closure, $referencedValues);
    }

    /**
     * @param Variable[] $referencedValues
     */
    private function isFoundInInnerUses(Closure $node, array $referencedValues): bool
    {
        return (bool) $this->betterNodeFinder->findFirstInFunctionLikeScoped(
            $node,
            function (Node $subNode) use ($referencedValues): bool {
                if (! $subNode instanceof Closure) {
                    return false;
                }

                foreach ($referencedValues as $referencedValue) {
                    $isFoundInInnerUses = (bool) array_filter(
                        $subNode->uses,
                        fn (ClosureUse $closureUse): bool => $closureUse->byRef && $this->nodeComparator->areNodesEqual(
                            $closureUse->var,
                            $referencedValue
                        )
                    );
                    if ($isFoundInInnerUses) {
                        return true;
                    }
                }

                return false;
            }
        );
    }

    /**
     * @return Variable[]
     */
    private function resolveReferencedUseVariablesFromClosure(Closure $closure): array
    {
        $referencedValues = [];

        /** @var ClosureUse $use */
        foreach ($closure->uses as $use) {
            if ($use->byRef) {
                $referencedValues[] = $use->var;
            }
        }

        return $referencedValues;
    }
}
