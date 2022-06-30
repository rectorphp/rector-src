<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

class SkipNoCaseInSwitch
{
    public function run($a)
    {
        switch ($a) {
        }

        echo 'executed';
    }
}
