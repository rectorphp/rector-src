<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\SimplifyConditionsRector\Fixture;

final class SkipNanUnsafeComparison
{
    public function run(float $value, $mixed, array $items)
    {
        $first = !($value > 0);
        $second = !($mixed >= 0);
        $third = !($items < [1]);
    }
}
