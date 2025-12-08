<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector\Fixture;

final class SkipCallDependencyOnArg
{
    public function run()
    {
        function ($foo)
        {
            return $foo->foo();
        };

        function ($foo)
        {
            return (new $foo)->foo();
        };

        function ($foo)
        {
            return $foo::foo();
        };

        function ($foo)
        {
            return ($foo . '\\Foo')::foo();
        };
    }
}
