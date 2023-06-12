<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use stdClass;

class MultiMethods
{
    /**
     * @return stdClass
     */
    function foo(): stdClass
    {
    }

    /**
     * @return stdClass
     */
    function bar(): stdClass
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use stdClass;

class MultiMethods
{
    function foo(): stdClass
    {
    }

    function bar(): stdClass
    {
    }
}

?>
