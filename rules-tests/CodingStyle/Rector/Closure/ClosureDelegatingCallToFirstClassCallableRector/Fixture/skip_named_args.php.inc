<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector\Fixture;

final class SkipNamedArgs
{
    public function run()
    {
        function ($foo)
        {
            return $foo->foo(foo: $foo);
        };

        function ($foo)
        {
            return Foo::foo(foo: $foo);
        };
    }
}
