<?php

declare(strict_types=1);

namespace Rector\CodeQuality\NodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use Rector\CodeQuality\ValueObject\KeyAndExpr;
use Rector\NodeAnalyzer\ExprAnalyzer;
use Rector\PhpParser\Node\Value\ValueResolver;

final readonly class VariableDimFetchAssignResolver
{
    public function __construct(
        private ExprAnalyzer $exprAnalyzer,
        private ValueResolver $valueResolver
    ) {
    }

    /**
     * @param Stmt[] $stmts
     * @return array<mixed, KeyAndExpr[]>
     */
    public function resolveFromStmtsAndVariable(array $stmts, ?Assign $emptyArrayAssign): array
    {
        $exprs = [];

        $key = 0;

        foreach ($stmts as $stmt) {
            if ($stmt instanceof Expression && $stmt->expr === $emptyArrayAssign) {
                continue;
            }

            if ($stmt instanceof Return_) {
                continue;
            }

            if (! $stmt instanceof Expression) {
                return [];
            }

            $stmtExpr = $stmt->expr;
            if (! $stmtExpr instanceof Assign) {
                return [];
            }

            $assign = $stmtExpr;

            $dimValues = [];

            $arrayDimFetch = $assign->var;
            while ($arrayDimFetch instanceof ArrayDimFetch) {
                if ($arrayDimFetch->dim instanceof Expr && $this->exprAnalyzer->isDynamicExpr($arrayDimFetch->dim)) {
                    return [];
                }

                $dimValues[] = $arrayDimFetch->dim instanceof Expr ? $this->valueResolver->getValue(
                    $arrayDimFetch->dim
                ) : $key;

                $arrayDimFetch = $arrayDimFetch->var;
            }

            ++$key;

            $this->setNestedKeysExpr($exprs, $dimValues, $assign->expr);
        }

        return $exprs;
    }

    /**
     * @param mixed[] $exprsByKeys
     * @param array<string|int> $keys
     */
    private function setNestedKeysExpr(array &$exprsByKeys, array $keys, Expr $expr): void
    {
        $reference = &$exprsByKeys;

        $keys = array_reverse($keys);

        foreach ($keys as $key) {
            // create intermediate arrays automatically
            $reference = &$reference[$key];
        }

        $reference = $expr;
    }
}
