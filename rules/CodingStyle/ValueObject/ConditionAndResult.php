<?php

declare(strict_types=1);

namespace Rector\CodingStyle\ValueObject;

use PhpParser\Node\Expr;

final readonly class ConditionAndResult
{
    public function __construct(
        private Expr $conditionExpr,
        private Expr $resultExpr
    ) {
    }

    public function getConditionExpr(): Expr
    {
        return $this->conditionExpr;
    }

    public function getResultExpr(): Expr
    {
        return $this->resultExpr;
    }
}
