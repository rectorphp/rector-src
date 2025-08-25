<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class NoStmtsWithoutComments
{
    public function run($foo)
    {
        switch ($foo) {
            case 'A':
                break;
            case 'B':
            case 'C':
                break;
            case 'D':
                $type = 'BAR';
                break;
            case 'E':
            case 'F':
            default:
                break;
        }
    }
}
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class NoStmtsWithoutComments
{
    public function run($foo)
    {
        switch ($foo) {
            case 'A':
            case 'B':
            case 'C':
            case 'E':
            case 'F':
            default:
                break;
            case 'D':
                $type = 'BAR';
                break;
        }
    }
}
