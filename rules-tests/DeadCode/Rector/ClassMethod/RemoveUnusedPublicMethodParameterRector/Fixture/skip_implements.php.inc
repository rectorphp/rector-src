<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPublicMethodParameterRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPublicMethodParameterRector\Source\interfaceWithMethod;

final class SkipImplements implements interfaceWithMethod
{
    public function run($a, $b)
    {
        echo $a;
    }
}
