<?php

namespace Rector\Tests\Php72\Rector\While_\WhileEachToForeachRector\Fixture;

final class SkipPointerMovedInLoop
{
    public function run(array $items)
    {
        reset($items);
        while (list($key, $item) = each($items)) {
            if ($item === 'skip') {
                next($items);
            }
        }
    }
}
