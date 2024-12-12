<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

class SkipPositiveIntOrZero
{
    /**
     * @return positive-int|0
     */
    function foo(): int
    {
        return rand(0, 1) ? 10 : 0;
    }
}
