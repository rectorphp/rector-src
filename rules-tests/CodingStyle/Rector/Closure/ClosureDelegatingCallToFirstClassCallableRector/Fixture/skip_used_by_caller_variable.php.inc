<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector\Fixture;

class SkipUsedByCallerVariable
{
    public function run()
    {
        $fn = function ($foo)
        {
            return $foo($foo);
        };

        var_dump($fn('strlen'));
    }
}
