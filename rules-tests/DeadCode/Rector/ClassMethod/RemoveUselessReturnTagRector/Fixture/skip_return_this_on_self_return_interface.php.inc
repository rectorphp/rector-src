<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

interface SkipRemoveThisOnSelfReturnInterface
{
    /**
     * @return $this
     */
    function foo(): self;
}
