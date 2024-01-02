<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\ValueObject;

use PhpParser\Node\Expr;

final readonly class AssignToVariable
{
    public function __construct(
        private string $variableName,
        private Expr $assignedExpr
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
