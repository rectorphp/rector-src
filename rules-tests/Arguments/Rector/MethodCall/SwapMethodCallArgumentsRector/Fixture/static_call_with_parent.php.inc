<?php

namespace Rector\Tests\Arguments\Rector\MethodCall\SwapMethodCallArgumentsRector\Fixture;

use Rector\Tests\Arguments\Rector\MethodCall\SwapMethodCallArgumentsRector\Source\MethodCaller;

final class ParentCall extends MethodCaller
{
    public function run()
    {
        parent::someCall($one, $two, $three);
    }
}

?>
-----
<?php

namespace Rector\Tests\Arguments\Rector\MethodCall\SwapMethodCallArgumentsRector\Fixture;

use Rector\Tests\Arguments\Rector\MethodCall\SwapMethodCallArgumentsRector\Source\MethodCaller;

final class ParentCall extends MethodCaller
{
    public function run()
    {
        parent::someCall($three, $two, $one);
    }
}

?>
