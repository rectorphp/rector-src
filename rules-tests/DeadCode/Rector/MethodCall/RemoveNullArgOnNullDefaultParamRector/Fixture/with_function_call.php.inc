<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector\Fixture;

function SomeFunctionWithDefaultNullArg(?string $someClass = null)
{
}

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

function SomeFunctionWithDefaultNullArg(?string $someClass = null)
{
}

class WithFunctionCall
{
    public function get()
    {
        SomeFunctionWithDefaultNullArg();
    }
}

?>
