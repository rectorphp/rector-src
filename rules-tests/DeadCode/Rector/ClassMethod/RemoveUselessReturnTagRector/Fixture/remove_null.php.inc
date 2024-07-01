<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

class RemoveNull
{
    /**
     * @return null
     */
    function foo(): null
    {
        return null;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

class RemoveNull
{
    function foo(): null
    {
        return null;
    }
}

?>
