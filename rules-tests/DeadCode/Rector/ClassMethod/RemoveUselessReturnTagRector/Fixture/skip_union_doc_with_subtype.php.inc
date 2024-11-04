<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use stdClass;

class SkipUnionDocWithSubType
{
    /**
     * @return stdClass|array<mixed>
     */
    function foo(): stdClass|array
    {

    }
}

?>