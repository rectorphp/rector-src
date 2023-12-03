<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use stdClass;

class SomethingAndVoid
{
    /**
     * @return stdClass
     */
    function foo(): void
    {

    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use stdClass;

class SomethingAndVoid
{
    function foo(): void
    {

    }
}

?>
