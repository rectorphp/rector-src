<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

class CopySwitchCommentWithReturn
{
    public function run(int $value)
    {
        // some comment here
        switch ($value) {
            case 1:
                return 1000;
                break;

            default:
                return 2000;
                break;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

class CopySwitchCommentWithReturn
{
    public function run(int $value)
    {
        // some comment here
        return match ($value) {
            1 => 1000,
            default => 2000,
        };
    }
}

?>
