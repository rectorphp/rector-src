<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

final class SkipFallThroughIntoDuplicate
{
    public function run($name)
    {
        switch ($name) {
            case 'a':
                return 'X';
            case 'b':
                echo 'log';
            case 'c':
                return 'X';
            case 'd':
                return 'Y';
        }
    }
}
