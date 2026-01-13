<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveVoidDocblockFromMagicMethodsRector\Fixture;

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

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveVoidDocblockFromMagicMethodsRector\Fixture;

class RemoveReturnConstruct
{
    function __construct()
    {
        //
    }
}

?>
