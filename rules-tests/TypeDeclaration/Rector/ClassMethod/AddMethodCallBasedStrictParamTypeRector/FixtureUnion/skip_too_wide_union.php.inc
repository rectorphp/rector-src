<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\FixtureUnion;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\String_;

final class SkipTooWideUnion
{
    public function run(MethodCall $methodCall, StaticCall $staticCall, String_ $string, Int_ $number)
    {
        $this->someExpr($methodCall);
        $this->someExpr($staticCall);
        $this->someExpr($string);
        $this->someExpr($number);
    }

    private function someExpr(Expr $expr)
    {
    }
}
