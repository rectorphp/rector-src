<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

class RemoveNull
{
    /**
     * @return null
     */
    function foo()
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
    function foo()
    {
        return null;
    }
}

?>
