<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

class A
{
    public function test()
    {
        return 0;
    }
}

class B extends A
{
    public function test(): string
    {
        $a = '1';

        return $a;
    }

}

?>
