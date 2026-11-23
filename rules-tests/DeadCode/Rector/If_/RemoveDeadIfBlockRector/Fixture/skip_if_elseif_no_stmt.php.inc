<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadIfBlockRector\Fixture;

class SkipIfElseifNoStmt
{
    public function run($condition)
    {
        if ($condition) {
            echo "something";
        } elseif (!$condition) {
        } elseif (true) {
            echo "global";
        }
    }
}
