<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source\SomeMultiArg;

class SkipNamedArguments
{
    public function run()
    {
        $containerBuilder = new SomeMultiArg();
        $containerBuilder->firstArgument(a: 7);
        $containerBuilder->firstArgument(1, b: 1);
        $containerBuilder->secondArgument(a: 7, b: 8);
        $containerBuilder->secondArgument(b: 7, a: 8);
        $containerBuilder->secondArgument(1, 2, c: 8);
        $containerBuilder->thirdArgument(c: 8);
        $containerBuilder->thirdArgument(b: 7, c: 8, a: 9);
        $containerBuilder->thirdArgument(1, b: 8, c: 9);
    }
}
