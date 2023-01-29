<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

class SkipIfChildClassHasTypedDifferent
{
    public function test()
    {
        return 0;
    }
}

class SomeChild extends SkipIfChildClassHasTypedDifferent
{
    public function test(): string
    {
        $a = '1';

        return $a;
    }

}
