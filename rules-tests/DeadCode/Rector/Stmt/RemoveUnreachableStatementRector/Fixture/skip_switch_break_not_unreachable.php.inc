<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector\Fixture;

final class SkipSwitchBreakNotUnreachable
{
    public function run(): bool {
        switch('test') {
            case 'test':
                if(rand(0, 1)) break;

                return false;
            default:
                return false;
        }

        return true;
    }
}
