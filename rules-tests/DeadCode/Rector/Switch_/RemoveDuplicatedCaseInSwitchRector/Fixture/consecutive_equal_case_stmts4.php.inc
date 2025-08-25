<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class ConsecutiveEqualCaseStmts4
{
    public function run($name)
    {
        switch ($name) {
            case 'a':
                return 'A';
            case 'b':
                return 'C';
            case 'd':
            case 'c':
            case 'f':
            default:
                return 'C';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class ConsecutiveEqualCaseStmts4
{
    public function run($name)
    {
        switch ($name) {
            case 'a':
                return 'A';
            case 'b':
            case 'd':
            case 'c':
            case 'f':
            default:
                return 'C';
        }
    }
}

?>
