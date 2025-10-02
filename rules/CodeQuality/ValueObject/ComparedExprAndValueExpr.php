<?php

declare(strict_types=1);

namespace Rector\CodeQuality\ValueObject;

use PhpParser\Node\Expr;

final readonly class ComparedExprAndValueExpr
{
    public function __construct(
        private Expr $comparedExpr,
        private Expr $valueExpr
    ) {
    }

    public function getComparedExpr(): Expr
    {
        return $this->comparedExpr;
    }

    public function getValueExpr(): Expr
    {
        return $this->valueExpr;
    }
}
