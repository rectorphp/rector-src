<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

final class SkipSwitchBreakNotUnreachable2
{
    public function run(): bool {
        beginning:
            echo 'test';

        switch('test') {
            case 'test':
                if(rand(0, 1)) break;

                goto beginning;
            default:
                return false;
        }

        return true;
    }
}
