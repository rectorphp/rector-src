<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class NoStmtsWithEqualComments
{
    public function run($foo)
    {
        switch ($foo) {
            case 'A':
                // Equal comment
                break;
            case 'B':
            case 'C':
                // Some comment
                break;
            case 'D':
                $type = 'BAR';
                break;
            case 'E':
            case 'F':
            default:
                // Equal comment
                break;
        }
    }
}
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class NoStmtsWithEqualComments
{
    public function run($foo)
    {
        switch ($foo) {
            case 'A':
            case 'E':
            case 'F':
            default:
                // Equal comment
                break;
            case 'B':
            case 'C':
                // Some comment
                break;
            case 'D':
                $type = 'BAR';
                break;
        }
    }
}
