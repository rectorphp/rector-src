<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Source\SomeClass;
use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Source\SomeConstantFloatType;

class UnionObject
{
    /**
     * @param SomeClass|SomeConstantFloatType $param
     */
    function foo(object $param)
    {

    }
}
