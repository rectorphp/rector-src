<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlockRector\Fixture;

class SkipEmptyIfWithMultipleElseifs
{
    public function run($flag, $a, $b)
    {
        if ($flag) {
        } elseif ($a) {
            echo 'A';
        } elseif ($b) {
            echo 'B';
        }
    }
}
