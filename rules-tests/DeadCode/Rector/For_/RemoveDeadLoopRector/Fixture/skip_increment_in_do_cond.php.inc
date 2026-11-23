<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadLoopRector\Fixture;

final class SkipIncrementInDoCond
{
    public function run()
    {
        $i = 0;
        do {
        } while ($i++ < 10);

        echo $i;
    }
}
