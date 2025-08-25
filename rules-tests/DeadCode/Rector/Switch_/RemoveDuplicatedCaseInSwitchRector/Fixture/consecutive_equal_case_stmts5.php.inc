<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class ConsecutiveEqualCaseStmts5
{
    public function run($name)
    {
        switch ($name) {
            case 'a':
                return 'A';
            case 'b':
                return 'C';
            default:
            case 'd':
            case 'c':
            case 'f':
                return 'C';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class ConsecutiveEqualCaseStmts5
{
    public function run($name)
    {
        switch ($name) {
            case 'a':
                return 'A';
            case 'b':
            default:
            case 'd':
            case 'c':
            case 'f':
                return 'C';
        }
    }
}

?>
