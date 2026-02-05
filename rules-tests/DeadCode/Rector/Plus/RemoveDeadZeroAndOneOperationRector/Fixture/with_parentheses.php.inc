<?php

namespace Rector\Tests\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector\Fixture;

class WithParentheses
{
    public function run()
    {
        $a = 1 * (2 + 3) * 4;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector\Fixture;

class WithParentheses
{
    public function run()
    {
        $a = (2 + 3) * 4;
    }
}

?>
