<?php

namespace Rector\Tests\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector\Fixture;

class MultiplyFunctionCall
{
    public function run(string $a)
    {
        $b = 1 * strlen($a) * 4;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector\Fixture;

class MultiplyFunctionCall
{
    public function run(string $a)
    {
        $b = strlen($a) * 4;
    }
}

?>
