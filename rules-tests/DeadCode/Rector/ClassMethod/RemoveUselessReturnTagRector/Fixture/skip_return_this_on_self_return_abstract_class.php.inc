<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

abstract class SkipRemoveThisOnSelfReturnAbstractClass
{
    /**
     * @return $this
     */
    function foo(): self
    {
        return $this;
    }
}
