<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachItemsAssignToEmptyArrayToAssignRector\Fixture;

class SkipMultipleAssignbeforeForeach
{
    public function run(array $items)
    {
        $items2 = [];
        $items2[] = 'hello';

        foreach ($items as $item) {
             $items2[] = $item;
        }
    }
}
