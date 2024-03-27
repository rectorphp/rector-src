<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

class WithNullableType
{
    /**
     * @param string|null $nullable
     */
    function foo(?string $nullable)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

class WithNullableType
{
    function foo(?string $nullable)
    {
    }
}

?>
