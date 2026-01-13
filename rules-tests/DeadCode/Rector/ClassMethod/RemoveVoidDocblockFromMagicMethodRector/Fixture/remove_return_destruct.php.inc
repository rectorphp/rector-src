<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveVoidDocblockFromMagicMethodRector\Fixture;

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

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveVoidDocblockFromMagicMethodRector\Fixture;

class RemoveReturnDestruct
{
    function __destruct()
    {
        //
    }
}

?>
