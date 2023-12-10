<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

class SkipRemoveNull
{
    /**
     * @return null
     */
    function foo(): ?int
    {
        return null;
    }
}
