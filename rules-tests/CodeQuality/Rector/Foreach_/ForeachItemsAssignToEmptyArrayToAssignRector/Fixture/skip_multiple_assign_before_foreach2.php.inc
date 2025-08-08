<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachItemsAssignToEmptyArrayToAssignRector\Fixture;

class SkipMultipleAssignBeforeForeach2
{
    public function run(array $items)
    {
        $items2 = [];

        if (rand(0, 1)) {
            $items2[] = 'hello';
        }

        foreach ($items as $item) {
             $items2[] = $item;
        }
    }
}
