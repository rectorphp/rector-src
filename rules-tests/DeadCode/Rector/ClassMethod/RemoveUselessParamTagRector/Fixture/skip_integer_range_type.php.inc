<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

final class SkipIntegerRangeType
{
    /**
     * @param positive-int $n
     */
    public function run(int $n)
    {
    }
}
?>
