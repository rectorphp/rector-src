<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPublicMethodParameterRector\Fixture;

final class SomeClass
{
    private function run($a, $b)
    {
        echo $a;
    }
}
