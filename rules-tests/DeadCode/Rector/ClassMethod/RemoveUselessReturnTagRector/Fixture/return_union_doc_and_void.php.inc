<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use Exception;

class ReturnUnionDocAndVoid
{
    /**
     * @return int|string
     */
    function foo(): void
    {
        throw new Exception();
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use Exception;

class ReturnUnionDocAndVoid
{
    function foo(): void
    {
        throw new Exception();
    }
}

?>
