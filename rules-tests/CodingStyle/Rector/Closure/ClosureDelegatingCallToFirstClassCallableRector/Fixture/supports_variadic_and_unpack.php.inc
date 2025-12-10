<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector\Source\SomeClassWithArgs;

final class SupportsVariadicAndUnpack
{
    public function run()
    {
        function (...$foo)
        {
            return SomeClassWithArgs::foo(...$foo);
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector\Source\SomeClassWithArgs;

final class SupportsVariadicAndUnpack
{
    public function run()
    {
        SomeClassWithArgs::foo(...);
    }
}

?>
