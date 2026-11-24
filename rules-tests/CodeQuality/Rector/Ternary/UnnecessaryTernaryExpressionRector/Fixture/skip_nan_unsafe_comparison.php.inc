<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\UnnecessaryTernaryExpressionRector\Fixture;

final class SkipNanUnsafeComparison
{
    public function run(float $value, $mixed)
    {
        $first = $value >= 0 ? false : true;
        $second = $mixed < 10 ? false : true;
    }
}
