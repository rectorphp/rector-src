<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\ValueObject;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;

final class AssignToVariable
{
    public function __construct(
        private readonly Variable $variable,
        private readonly string $variableName,
        private readonly Expr $assignedExpr
    ) {
    }

    public function getVariable(): Variable
    {
        return $this->variable;
    }

    public function getVariableName(): string
    {
        return $this->variableName;
    }

    public function getAssignedExpr(): Expr
    {
        return $this->assignedExpr;
    }
}
