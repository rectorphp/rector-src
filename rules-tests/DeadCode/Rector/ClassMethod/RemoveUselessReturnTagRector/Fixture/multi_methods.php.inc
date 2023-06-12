<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use DateTime;
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
     * @return DateTime
     */
    function bar(): DateTime
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use DateTime;
use stdClass;

class MultiMethods
{
    function foo(): stdClass
    {
    }

    function bar(): DateTime
    {
    }
}

?>
