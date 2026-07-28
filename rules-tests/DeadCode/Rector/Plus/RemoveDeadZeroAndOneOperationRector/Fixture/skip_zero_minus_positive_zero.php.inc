<?php

namespace Rector\Tests\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector\Fixture;

final class SkipZeroMinusPositiveZero
{
    protected function run()
    {
        $value = 0.0;
        var_dump((string) $value);

        $value = 0 - $value;
        var_dump((string) $value);
    }
}
