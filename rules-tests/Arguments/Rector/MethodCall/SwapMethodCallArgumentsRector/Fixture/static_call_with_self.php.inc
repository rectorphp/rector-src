<?php

namespace Rector\Tests\Arguments\Rector\MethodCall\SwapMethodCallArgumentsRector\Fixture;

use Rector\Tests\Arguments\Rector\MethodCall\SwapMethodCallArgumentsRector\Source\MethodCaller;

final class SelfCall extends MethodCaller
{
    public function run()
    {
        self::someCall($one, $two, $three);
    }
}

?>
-----
<?php

namespace Rector\Tests\Arguments\Rector\MethodCall\SwapMethodCallArgumentsRector\Fixture;

use Rector\Tests\Arguments\Rector\MethodCall\SwapMethodCallArgumentsRector\Source\MethodCaller;

final class SelfCall extends MethodCaller
{
    public function run()
    {
        self::someCall($three, $two, $one);
    }
}

?>
