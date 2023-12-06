<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

class RemoveReturnMixed2
{
    /**
     * @return mixed
     */
    function foo(): mixed
    {
        return null;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

class RemoveReturnMixed2
{
    function foo(): mixed
    {
        return null;
    }
}

?>
