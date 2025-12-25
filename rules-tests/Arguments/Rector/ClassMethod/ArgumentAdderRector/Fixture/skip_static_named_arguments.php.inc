<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source\SomeMultiArg;

class SkipStaticNamedArguments extends SomeMultiArg
{
    public function firstArgument($a = 1, $b = 2, $c = 3)
    {
        parent::firstArgument(a: 7);
        parent::firstArgument(1, b: 1);
    }

    public function secondArgument($a = 1, $b = 2, $c = 3)
    {
        parent::secondArgument(a: 7, b: 8);
        parent::secondArgument(b: 7, a: 8);
        parent::secondArgument(1, 2, c: 8);
    }

    public function thirdArgument($a = 1, $b = 2, $c = 3)
    {
        parent::thirdArgument(c: 8);
        parent::thirdArgument(b: 7, c: 8, a: 9);
        parent::thirdArgument(1, b: 8, c: 9);
    }
}
