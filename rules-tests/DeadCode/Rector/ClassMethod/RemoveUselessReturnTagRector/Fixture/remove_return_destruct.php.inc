<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

class RemoveReturnDestruct
{
    /**
     * @return void
     */
    function __destruct()
    {
        //
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

class RemoveReturnDestruct
{
    function __destruct()
    {
        //
    }
}

?>
