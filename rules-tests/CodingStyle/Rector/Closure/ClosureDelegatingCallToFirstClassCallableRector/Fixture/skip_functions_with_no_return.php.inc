<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector\Fixture;

final class SkipFunctionsWithNoReturn
{
    public function run()
    {
        function ($foo)
        {
            FooBar::foo($foo);
        };

        $bar = null;

        function ($foo) use ($bar)
        {
            $bar->foo($foo);
        };
    }
}
