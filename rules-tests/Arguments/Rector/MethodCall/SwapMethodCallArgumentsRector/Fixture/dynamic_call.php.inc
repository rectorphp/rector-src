<?php

namespace Rector\Tests\Arguments\Rector\MethodCall\SwapMethodCallArgumentsRector\Fixture;

use Rector\Tests\Arguments\Rector\MethodCall\SwapMethodCallArgumentsRector\Source\MethodCaller;

final class Fixture
{
    public function run(MethodCaller $caller)
    {
        $caller->someCall($one, $two, $three);
    }
}

?>
-----
<?php

namespace Rector\Tests\Arguments\Rector\MethodCall\SwapMethodCallArgumentsRector\Fixture;

use Rector\Tests\Arguments\Rector\MethodCall\SwapMethodCallArgumentsRector\Source\MethodCaller;

final class Fixture
{
    public function run(MethodCaller $caller)
    {
        $caller->someCall($three, $two, $one);
    }
}

?>
