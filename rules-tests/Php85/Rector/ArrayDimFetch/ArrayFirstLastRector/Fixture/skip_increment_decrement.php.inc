<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector\Fixture;

final class SkipIncrementDecrement
{
    public function run(array $counts)
    {
        ++$counts[array_key_last($counts)];
        $counts[array_key_first($counts)]++;
        --$counts[array_key_last($counts)];
        $counts[array_key_first($counts)]--;

        $longest = max(0, ++$counts[array_key_last($counts)]);
    }
}
