<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveVoidDocblockFromMagicMethodRector\Fixture;

class RemoveReturnConstruct
{
    /**
     * @return void
     */
    function __construct()
    {
        //
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveVoidDocblockFromMagicMethodRector\Fixture;

class RemoveReturnConstruct
{
    function __construct()
    {
        //
    }
}

?>
