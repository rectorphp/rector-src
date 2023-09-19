<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\NumberCompareToMaxFuncCallRector\Fixture;

final class SkipElseAnotherValue
{
    public function run(int $value)
    {
        return $value > 100 ? $value : 55;
    }
}
