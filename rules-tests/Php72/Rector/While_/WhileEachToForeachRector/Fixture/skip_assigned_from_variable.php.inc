<?php

namespace Rector\Tests\Php72\Rector\While_\WhileEachToForeachRector\Fixture;

final class SkipAssignedFromVariable
{
    public function run(array $source)
    {
        // the copy keeps the internal pointer of $source
        $items = $source;
        while (list(, $item) = each($items)) {
            echo $item;
        }
    }
}
