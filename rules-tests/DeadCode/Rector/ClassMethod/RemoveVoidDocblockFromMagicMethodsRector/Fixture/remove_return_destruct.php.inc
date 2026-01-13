<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveVoidDocblockFromMagicMethodsRector\Fixture;

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

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveVoidDocblockFromMagicMethodsRector\Fixture;

class RemoveReturnDestruct
{
    function __destruct()
    {
        //
    }
}

?>
