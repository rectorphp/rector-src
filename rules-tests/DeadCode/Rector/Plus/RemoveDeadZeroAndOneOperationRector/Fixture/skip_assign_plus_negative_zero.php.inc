<?php

namespace Rector\Tests\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector\Fixture;

final class SkipAssignPlusNegativeZero
{
    protected function run()
    {
        $value = round(-0.001, 2);
        var_dump((string) $value);

        $value += 0;
        var_dump((string) $value);
    }
}
