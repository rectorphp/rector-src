<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use Exception;

class SkipReturnNeverDocAndVoid
{
    /**
     * @return never
     */
    function foo(): void
    {
        throw new Exception();
    }
}
