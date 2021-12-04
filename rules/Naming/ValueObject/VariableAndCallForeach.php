<?php

declare(strict_types=1);

namespace Rector\Naming\ValueObject;

use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;

final class VariableAndCallForeach
{
    public function __construct(
        private readonly Variable $variable,
        private readonly FuncCall | StaticCall | MethodCall $expr,
        private readonly string $variableName,
        private readonly ClassMethod | Function_ | Closure $functionLike
    ) {
    }

    public function getVariable(): Variable
    {
        return $this->variable;
    }

    public function getCall(): FuncCall | MethodCall | StaticCall
    {
        return $this->expr;
    }

    public function getVariableName(): string
    {
        return $this->variableName;
    }

    public function getFunctionLike(): Closure | ClassMethod | Function_
    {
        return $this->functionLike;
    }
}
