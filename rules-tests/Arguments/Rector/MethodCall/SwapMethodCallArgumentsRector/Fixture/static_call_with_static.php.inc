<?php

namespace Rector\Tests\Arguments\Rector\MethodCall\SwapMethodCallArgumentsRector\Fixture;

use Rector\Tests\Arguments\Rector\MethodCall\SwapMethodCallArgumentsRector\Source\MethodCaller;

class StaticCall extends MethodCaller
{
    public function run()
    {
        static::someCall($one, $two, $three);
    }
}

?>
-----
<?php

namespace Rector\Tests\Arguments\Rector\MethodCall\SwapMethodCallArgumentsRector\Fixture;

use Rector\Tests\Arguments\Rector\MethodCall\SwapMethodCallArgumentsRector\Source\MethodCaller;

class StaticCall extends MethodCaller
{
    public function run()
    {
        static::someCall($three, $two, $one);
    }
}

?>
