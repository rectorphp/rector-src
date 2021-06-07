<?php

declare(strict_types=1);

namespace Rector\Naming\ValueObject;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;

final class VariableAndCallAssign
{
    /**
     * @param FuncCall|StaticCall|MethodCall $call
     */
    public function __construct(
        private Expr $call
    ) {
    }

    /**
     * @return FuncCall|StaticCall|MethodCall
     */
    public function getCall(): Expr
    {
        return $this->call;
    }
}
