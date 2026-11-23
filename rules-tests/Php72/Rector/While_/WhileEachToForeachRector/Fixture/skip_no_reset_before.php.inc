<?php

namespace Rector\Tests\Php72\Rector\While_\WhileEachToForeachRector\Fixture;

final class SkipNoResetBefore
{
    public function run(array $items)
    {
        // the caller may have moved the internal pointer, each() continues from there
        while (list(, $item) = each($items)) {
            echo $item;
        }
    }
}
