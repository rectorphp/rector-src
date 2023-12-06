<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use stdClass;

class RemoveReturnMixed
{
    /**
     * @return mixed
     */
    function foo(): stdClass
    {
        return new stdClass();
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use stdClass;

class RemoveReturnMixed
{
    function foo(): stdClass
    {
        return new stdClass();
    }
}

?>
