<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPublicMethodParameterRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPublicMethodParameterRector\Source\AbstractClassWithMethod;

final class SkipExtends extends AbstractClassWithMethod
{
    public function run($a, $b)
    {
        echo $a;
    }
}
