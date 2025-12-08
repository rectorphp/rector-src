<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector\Fixture;

class SkipVariadic
{
    public function run()
    {
        $fn = function (...$args) {
            return array_sum($args);
        };

        $fn(1, 2, 3);
    }
}

