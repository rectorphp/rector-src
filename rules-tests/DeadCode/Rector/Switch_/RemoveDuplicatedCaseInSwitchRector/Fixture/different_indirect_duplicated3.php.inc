<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class DiffrentIndirectDuplicated3
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
            case 'f':
                return 'C';
            case 'g':
                return 'A';
            case 'h':
                return 'B';
            case 'i':
                return 'C';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class DiffrentIndirectDuplicated3
{
    public function run($name)
    {
        switch ($name) {
            case 'a':
            case 'd':
            case 'g':
                return 'A';
            case 'b':
            case 'e':
            case 'h':
                return 'B';
            case 'c':
            case 'f':
            case 'i':
                return 'C';
        }
    }
}

?>
