<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

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

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

class WithNullableType
{
    /**
     * @param $nullable
     */
    function foo(?string $nullable)
    {
    }
}

?>
