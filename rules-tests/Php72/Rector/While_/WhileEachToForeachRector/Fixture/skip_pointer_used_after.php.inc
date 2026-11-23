<?php

namespace Rector\Tests\Php72\Rector\While_\WhileEachToForeachRector\Fixture;

final class SkipPointerUsedAfter
{
    public function run(array $items)
    {
        reset($items);
        while (list(, $item) = each($items)) {
            echo $item;
        }

        return key($items);
    }
}
