<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPublicMethodParameterRector\Source;

abstract class AbstractClassWithMethod
{
    public function run($a, $b)
    {
    }
}
