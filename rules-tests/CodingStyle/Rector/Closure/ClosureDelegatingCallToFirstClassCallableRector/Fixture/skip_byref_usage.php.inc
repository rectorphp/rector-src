<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector\Fixture;

final class SkipByRefUsage
{
    public function run()
    {
        function (&$foo)
        {
            return Foo::foo($foo);
        };
    }
}
