<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

class SkipRemoveParamUnion
{
    /**
     * @param array|string $a
     */
    function foo($a)
    {

    }
}

?>
