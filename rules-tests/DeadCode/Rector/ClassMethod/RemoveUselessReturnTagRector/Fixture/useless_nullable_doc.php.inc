<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use stdClass;

class UselessNullableDoc
{
    /**
     * @return stdClass|null
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

class UselessNullableDoc
{
    function foo(): stdClass
    {
        return new stdClass();
    }
}

?>
