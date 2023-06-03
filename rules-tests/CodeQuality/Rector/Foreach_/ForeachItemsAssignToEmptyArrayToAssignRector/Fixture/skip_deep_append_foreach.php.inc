<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachItemsAssignToEmptyArrayToAssignRector\Fixture;

class SkipDeepAppendForeach
{
    public function run(array $items)
    {
        $items2 = [];

        if (rand(0, 1)) {
            foreach (['foo', 'bar'] as $item) {
                $items2[] = $item;
            }
        }

        foreach ($items as $item) {
            $items2[] = $item;
        }
    }
}

