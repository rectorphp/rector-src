<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

final class SkipSwitchCaseNestedLoopBreak
{
    public function run($a, array $items)
    {
        switch ($a) {
            case 'a':
                foreach ($items as $item) {
                    break;
                }

                return 'A';
            default:
                return 'B';
        }

        echo 'kept, as break targets are not resolved';
    }
}
