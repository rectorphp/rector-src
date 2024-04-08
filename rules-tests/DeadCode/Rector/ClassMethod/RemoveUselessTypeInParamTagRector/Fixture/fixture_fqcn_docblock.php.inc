<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

use stdClass;

class SomeClassFQCNDocblock
{
    /**
     * @param \stdClass $a
     */
    function foo(stdClass $a)
    {

    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

use stdClass;

class SomeClassFQCNDocblock
{
    /**
     * @param $a
     */
    function foo(stdClass $a)
    {

    }
}

?>
