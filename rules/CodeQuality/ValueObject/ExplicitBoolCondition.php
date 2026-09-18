<?php

declare(strict_types=1);

namespace Rector\CodeQuality\ValueObject;

use PhpParser\Node\Expr;

final readonly class ExplicitBoolCondition
{
    public function __construct(
        private Expr $expr,
        private bool $isNegated
    ) {
    }

    public function getConditionNode(): Expr
    {
        return $this->expr;
    }

    public function isNegated(): bool
    {
        return $this->isNegated;
    }
}
