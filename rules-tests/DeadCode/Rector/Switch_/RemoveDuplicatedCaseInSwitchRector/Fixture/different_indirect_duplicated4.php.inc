<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class DifferentIndirectDuplicated4
{
    public function run()
    {
        switch ($item) {
            case 'a':
            case 'b':
                return 'val1';
            case 'c':
                return 'val2';
            case 'd':
            case 'e':
                return 'val5';
            case 'f':
            case 'g':
            case 'h':
                return 'val2';
            case 'i':
                return 'val3';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class DifferentIndirectDuplicated4
{
    public function run()
    {
        switch ($item) {
            case 'a':
            case 'b':
                return 'val1';
            case 'c':
            case 'f':
            case 'g':
            case 'h':
                return 'val2';
            case 'd':
            case 'e':
                return 'val5';
            case 'i':
                return 'val3';
        }
    }
}

?>
