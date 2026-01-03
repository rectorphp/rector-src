<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessVoidReturnFromDocblockOnVoidMagicMethodsRector\Fixture;

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

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessVoidReturnFromDocblockOnVoidMagicMethodsRector\Fixture;

class RemoveReturnConstruct
{
    function __construct()
    {
        //
    }
}

?>
