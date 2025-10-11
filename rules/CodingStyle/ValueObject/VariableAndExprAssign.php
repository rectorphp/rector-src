<?php

declare(strict_types=1);

namespace Rector\CodingStyle\ValueObject;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;

final readonly class VariableAndExprAssign
{
    public function __construct(
        private Variable $variable,
        private Expr $expr,
    ) {
    }

    public function getVariable(): Variable
    {
        return $this->variable;
    }

    public function getExpr(): Expr
    {
        return $this->expr;
    }
}
