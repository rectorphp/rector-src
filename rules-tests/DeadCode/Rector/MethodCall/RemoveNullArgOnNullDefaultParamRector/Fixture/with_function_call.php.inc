<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector\Fixture;

function SomeFunctionWithDefaultNullArg(?string $someClass = null)
{
}

SomeFunctionWithDefaultNullArg(null);

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector\Fixture;

function SomeFunctionWithDefaultNullArg(?string $someClass = null)
{
}

SomeFunctionWithDefaultNullArg();

?>
