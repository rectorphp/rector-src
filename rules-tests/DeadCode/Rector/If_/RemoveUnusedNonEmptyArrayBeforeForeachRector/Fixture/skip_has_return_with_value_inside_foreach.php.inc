<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

final class SkipHasReturnWithValueInsideForeach
{
    public function run(array $items)
    {
        if (empty($items)) {
            return;
        }

        foreach ($items as $item) {
            return 1;
        }
    }
}
