<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class DiffrentIndirectDuplicated
{
    public function run($name)
    {
        switch ($name) {
            case 'a':
                return 'A';
            case 'b':
                return 'B';
            case 'c';
                return 'C';
            case 'd':
                return 'A';
            case 'e':
                return 'B';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class DiffrentIndirectDuplicated
{
    public function run($name)
    {
        switch ($name) {
            case 'a':
            case 'd':
                return 'A';
            case 'b':
            case 'e':
                return 'B';
            case 'c';
                return 'C';
        }
    }
}

?>
