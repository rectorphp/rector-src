<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlockRector\Fixture;

class SkipEmptyMiddleElseifBeforeElse
{
    public function run($a, $b)
    {
        if ($a) {
            echo 'A';
        } elseif ($b) {
        } else {
            echo 'ELSE';
        }
    }
}
