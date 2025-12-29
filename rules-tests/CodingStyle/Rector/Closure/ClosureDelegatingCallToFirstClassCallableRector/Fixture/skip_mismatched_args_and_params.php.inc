<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector\Fixture;

final class SkipMismatchedArgsAndParams
{
    public function run()
    {
        function ($foo, $bar)
        {
            return FooBar::foo($bar, $foo);
        };

        $bar = null;

        function ($foo, $barFoo) use ($bar)
        {
            return $bar->foo($barFoo, $foo);
        };
    }
}
