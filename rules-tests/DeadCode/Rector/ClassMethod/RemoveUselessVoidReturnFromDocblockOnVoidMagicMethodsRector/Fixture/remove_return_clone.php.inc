<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessVoidReturnFromDocblockOnVoidMagicMethodsRector\Fixture;

class RemoveReturnClone
{
    /**
     * Other comments
     *
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

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessVoidReturnFromDocblockOnVoidMagicMethodsRector\Fixture;

class RemoveReturnClone
{
    /**
     * Other comments
     */
    function __clone()
    {
        //
    }
}

?>
