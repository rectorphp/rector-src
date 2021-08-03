<?php

namespace Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector\Fixture;

final class OtherClass3
{
    public function __construct($property = null)
    {
    }

    public function someMethod()
    {
    }
}

final class HasConstructWithDefaultValueParameter
{
    public function run()
    {
        return [OtherClass3::class, 'someMethod'];
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector\Fixture;

final class OtherClass3
{
    public function __construct($property = null)
    {
    }

    public function someMethod()
    {
    }
}

final class HasConstructWithDefaultValueParameter
{
    public function run()
    {
        return function () {
            return (new \Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector\Fixture\OtherClass3())->someMethod();
        };
    }
}

?>
