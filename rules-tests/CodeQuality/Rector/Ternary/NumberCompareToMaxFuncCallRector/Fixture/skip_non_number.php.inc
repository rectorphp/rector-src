<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\NumberCompareToMaxFuncCallRector\Fixture;

final class SkipNonNumber
{
    public function run($value)
    {
        return 100 < $value ? $value : 100;
    }
}
