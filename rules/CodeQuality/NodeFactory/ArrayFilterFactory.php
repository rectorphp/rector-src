<?php

declare(strict_types=1);

namespace Rector\CodeQuality\NodeFactory;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Foreach_;

final class ArrayFilterFactory
{
    public function createSimpleFuncCallAssign(
        Foreach_ $foreach,
        string $funcName,
        ArrayDimFetch $arrayDimFetch
    ): Assign {
        $string = new String_($funcName);

        $args = [new Arg($foreach->expr), new Arg($string)];
        $arrayFilterFuncCall = new FuncCall(new Name('array_filter'), $args);

        return new Assign($arrayDimFetch->var, $arrayFilterFuncCall);
    }
}
