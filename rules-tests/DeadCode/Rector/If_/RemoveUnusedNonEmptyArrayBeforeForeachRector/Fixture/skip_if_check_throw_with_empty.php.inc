<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

use Exception;

final class SkipIfCheckThrowWithEmpty
{
    public function run(array $items)
    {
        if (empty($items)) {
            throw new Exception('items must not be empty');
        }

        foreach ($items as $item) {
            echo $item;
        }
    }
}
