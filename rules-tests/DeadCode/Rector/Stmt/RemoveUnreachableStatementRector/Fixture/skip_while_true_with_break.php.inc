<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

class SkipWhileTrueWithBreak
{
    public function run($a)
    {
        while (true) {
            if ($a) {
                break;
            }

            $a = doSomething();
        }

        echo 'still reachable after break';
    }
}
