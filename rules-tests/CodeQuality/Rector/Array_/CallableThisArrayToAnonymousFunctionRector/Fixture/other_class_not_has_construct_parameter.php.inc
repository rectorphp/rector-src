<?php

namespace Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector\Fixture;

final class OtherClass2
{
    public function __construct()
    {
    }

    public function someMethod()
    {
    }
}

final class OtherClassNotHasConstructParameter
{
    public function run()
    {
        return [OtherClass2::class, 'someMethod'];
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector\Fixture;

final class OtherClass2
{
    public function __construct()
    {
    }

    public function someMethod()
    {
    }
}

final class OtherClassNotHasConstructParameter
{
    public function run()
    {
        return function () {
            return (new \Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector\Fixture\OtherClass2())->someMethod();
        };
    }
}

?>
