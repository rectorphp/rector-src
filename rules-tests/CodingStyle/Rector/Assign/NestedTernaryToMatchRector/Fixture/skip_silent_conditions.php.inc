<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\Rector\Assign\NestedTernaryToMatchRector\Fixture;

final class SkipSilentConditions
{
    public function run($value)
    {
        $value = 5;

        $result = $value > 10 ? 'greater than 10' : ($value === 10 ?: 'less than 10');
    }
}
