<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

class SkipSwitchNoDefault
{
    public function run($a)
    {
        switch ($a) {
            case 'a':
                return 'A';
            case 'b':
                return 'B';
        }

        echo 'executed';
    }
}

