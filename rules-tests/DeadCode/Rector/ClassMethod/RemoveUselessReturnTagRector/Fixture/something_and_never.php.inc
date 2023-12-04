<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use stdClass;

class SomethingAndNever
{
    /**
     * @return stdClass
     */
    function foo(): never
    {

    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use stdClass;

class SomethingAndNever
{
    function foo(): never
    {

    }
}

?>
