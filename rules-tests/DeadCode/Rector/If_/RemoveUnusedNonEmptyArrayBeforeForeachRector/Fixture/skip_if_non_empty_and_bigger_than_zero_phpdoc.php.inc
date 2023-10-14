<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

final class SkipIfNonEmptyAndBiggerThanZeroPhpdoc
{
    /**
     * @param array $items
     */
    public function run($items)
    {
        if ($items && count($items) > 0) {
            foreach ($items as $item) {
                echo $item;
            }
        }
    }
}

?>
