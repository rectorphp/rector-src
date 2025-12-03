<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

class RemoveReturnClone
{
    /**
     * @return void
     */
    function __clone()
    {
        //
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

class RemoveReturnClone
{
    function __clone()
    {
        //
    }
}

?>
