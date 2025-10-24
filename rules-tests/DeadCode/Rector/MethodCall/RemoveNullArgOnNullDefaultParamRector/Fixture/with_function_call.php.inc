<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector\Fixture;

use function Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector\Source\SomeFunctionWithDefaultNullArg;

class WithFunctionCall
{
    public function get()
    {
        SomeFunctionWithDefaultNullArg(null);
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector\Fixture;

use function Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector\Source\SomeFunctionWithDefaultNullArg;

class WithFunctionCall
{
    public function get()
    {
        SomeFunctionWithDefaultNullArg(null);
    }
}

?>
