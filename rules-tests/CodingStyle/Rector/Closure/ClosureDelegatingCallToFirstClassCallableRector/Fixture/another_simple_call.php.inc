<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector\Source\SomeClassWithArgs;

final class AnotherSimpleCall
{
    public function run()
    {
        function ($bar)
        {
            return SomeClassWithArgs::foo($bar);
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector\Source\SomeClassWithArgs;

final class AnotherSimpleCall
{
    public function run()
    {
        SomeClassWithArgs::foo(...);
    }
}

?>
