<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use stdClass;

class UnionDocNeverSubType
{
    /**
     * @return stdClass|array
     */
    function foo(): stdClass
    {

    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use stdClass;

class UnionDocNeverSubType
{
    function foo(): stdClass
    {

    }
}

?>
