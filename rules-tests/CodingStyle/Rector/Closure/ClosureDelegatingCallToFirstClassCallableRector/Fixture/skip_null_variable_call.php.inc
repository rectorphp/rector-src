<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector\Fixture;

final class SkipNullVariable
{
    public function run()
    {
        $bar = null;

        function ($foo) use ($bar)
        {
            return $bar->foo($foo);
        };
    }
}
