<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\ValueObject;

use PhpParser\Node\Expr;

final class AssignToVariable
{
    public function __construct(
        private readonly string $variableName,
        private readonly Expr $assignedExpr
    ) {
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
