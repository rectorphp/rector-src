<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

class SkipSwitchCaseEmptyStmt
{
    public function run($a)
    {
        switch ($a) {
            case 'a':
        }

        echo 'executed';
    }
}

