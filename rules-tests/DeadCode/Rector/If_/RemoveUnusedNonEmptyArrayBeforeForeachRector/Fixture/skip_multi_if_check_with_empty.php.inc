<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

final class SkipMultiIfCheckWithEmpty
{
    public function run(array $items, array $items2)
    {
        if (empty($items)) {
            return;
        }

        foreach ($items as $item) {
            echo $item;
        }

        if (empty($items2)) {
            return;
        }

        foreach ($items2 as $item2) {
            echo $item2;
        }
    }
}
