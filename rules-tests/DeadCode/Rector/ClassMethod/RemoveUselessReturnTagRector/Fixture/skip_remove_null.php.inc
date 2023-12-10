<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

class RemoveNull
{
    /**
     * @return null
     */
    function foo(): ?int
    {
        return null;
    }
}
