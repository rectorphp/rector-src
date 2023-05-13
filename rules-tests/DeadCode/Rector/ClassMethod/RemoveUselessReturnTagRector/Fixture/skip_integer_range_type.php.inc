<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

final class SkipIntegerRangeType
{
    /**
     * @return positive-int
     */
    public function run(): int
    {
    }
}
