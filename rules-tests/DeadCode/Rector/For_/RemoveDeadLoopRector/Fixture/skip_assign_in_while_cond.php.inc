<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadLoopRector\Fixture;

final class SkipAssignInWhileCond
{
    public function run(int $i)
    {
        while (($i -= 1) > 0) {
        }

        echo $i;
    }
}
