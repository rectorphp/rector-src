<?php

namespace Rector\Tests\Php72\Rector\While_\WhileEachToForeachRector\Fixture;

final class SkipByReferenceParam
{
    public function run(array &$items)
    {
        // the caller sees the pointer at the end after each()
        reset($items);
        while (list(, $item) = each($items)) {
            echo $item;
        }
    }
}
