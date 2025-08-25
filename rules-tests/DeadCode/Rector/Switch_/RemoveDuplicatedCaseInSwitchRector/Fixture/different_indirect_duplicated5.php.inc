<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class DifferentIndirectDuplicated5
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
                return 'C';
            case 'e':
                return 'A';
            default:
                return 'C';
            case 'i':
                return 'A';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector\Fixture;

class DifferentIndirectDuplicated5
{
    public function run($name)
    {
        switch ($name) {
            case 'a':
            case 'e':
            case 'i':
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
