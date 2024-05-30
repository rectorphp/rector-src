<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPublicMethodParameterRector\Fixture;

final class SkipInMiddle
{
    public function run($a, $b, $c)
    {
        echo $a;
        echo $c;
    }
}

?>