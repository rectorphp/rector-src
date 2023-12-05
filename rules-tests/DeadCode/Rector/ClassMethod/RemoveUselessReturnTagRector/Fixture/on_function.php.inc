<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use stdClass;

/**
 * @return stdClass
 */
function foo(): stdClass
{
    return new stdClass();
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use stdClass;

function foo(): stdClass
{
    return new stdClass();
}

?>
