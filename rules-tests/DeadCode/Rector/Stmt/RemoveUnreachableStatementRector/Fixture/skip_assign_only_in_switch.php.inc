<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

class SkipAssignOnlyInSwitch
{
    public function run($a)
    {
        switch ($a) {
            case 'a':
                $result = 'A';
                break;
        }

        echo 'executed';
    }
}
