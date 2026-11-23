<?php

namespace Rector\Tests\Php72\Rector\While_\WhileEachToForeachRector\Fixture;

final class SkipSecondLoop
{
    public function run(array $items)
    {
        reset($items);
        while (list(, $item) = each($items)) {
            echo $item;
        }

        // the pointer is at the end, this loop never runs
        while (list(, $item) = each($items)) {
            echo $item;
        }
    }
}
