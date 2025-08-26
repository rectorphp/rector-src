<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class SkipWithOneCase
{
    public function run($foo)
    {
        switch ($foo) {
            case 'A':
                return 'a';
        }
    }
}
