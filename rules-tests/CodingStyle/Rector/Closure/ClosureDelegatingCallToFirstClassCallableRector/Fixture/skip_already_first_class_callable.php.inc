<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector\Fixture;

final class SkipAlreadyFirstClassCallable
{
    public function run()
    {
        function ($parameter) { return Call::to(...); };
    }
}

