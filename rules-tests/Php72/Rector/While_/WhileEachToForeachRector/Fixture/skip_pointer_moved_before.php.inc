<?php

namespace Rector\Tests\Php72\Rector\While_\WhileEachToForeachRector\Fixture;

final class SkipPointerMovedBefore
{
    public function run(array $items)
    {
        reset($items);
        next($items);
        while (list(, $item) = each($items)) {
            echo $item;
        }
    }
}
