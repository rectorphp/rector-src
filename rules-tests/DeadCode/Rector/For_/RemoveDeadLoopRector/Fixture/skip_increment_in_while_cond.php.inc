<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadLoopRector\Fixture;

final class SkipIncrementInWhileCond
{
    public function run()
    {
        $i = 0;
        while (++$i < 10) {
        }

        echo $i;
    }
}
