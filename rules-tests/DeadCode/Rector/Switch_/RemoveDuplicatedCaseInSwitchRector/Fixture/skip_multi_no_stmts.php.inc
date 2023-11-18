<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class SkipMultiNoStmts
{
    public function run($foo)
    {
        switch ($foo) {
            default:
            case 'A':
                // Nothing to do
                break;
            case 'B':
            case 'C':
                // Nothing to do 2
                break;
            case 'D':
                $type = 'BAR';
                break;
            case 'E':
            case 'F':
                // Nothing to do 3
                break;
        }
    }
}