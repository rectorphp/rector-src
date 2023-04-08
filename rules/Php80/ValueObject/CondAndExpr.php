<?php

declare(strict_types=1);

namespace Rector\Php80\ValueObject;

use PhpParser\Node\Expr;

final class CondAndExpr
{
    /**
     * @param Expr[]|null $condExprs
     */
    public function __construct(
        private readonly array|null $condExprs,
        private readonly Expr $expr,
        private readonly string $matchKind
    ) {
    }

    public function getExpr(): Expr
    {
        return $this->expr;
    }

    /**
     * @return Expr[]|null
     */
    public function getCondExprs(): array|null
    {
        // internally checked by PHPStan, cannot be empty array
        if ($this->condExprs === []) {
            return null;
        }

        return $this->condExprs;
    }

    public function getMatchKind(): string
    {
        return $this->matchKind;
    }

    public function equalsMatchKind(string $matchKind): bool
    {
        return $this->matchKind === $matchKind;
    }
}
