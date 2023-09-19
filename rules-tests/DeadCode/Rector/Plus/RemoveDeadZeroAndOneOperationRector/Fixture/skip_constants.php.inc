<?php

namespace Rector\Tests\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector\Fixture;

class SkipConstants
{
    public const ONE = 1;

    public function run()
    {
        $value = 5 * self::ONE;
        $value = 5 / self::ONE;
        $value = self::ONE * 5;
        $value = self::ONE / 5;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector\Fixture;

class SkipConstants
{
    public const ONE = 1;

    public function run()
    {
        $value = 5 * self::ONE;
        $value = 5 / self::ONE;
        $value = self::ONE * 5;
        $value = self::ONE / 5;
    }
}

?>
