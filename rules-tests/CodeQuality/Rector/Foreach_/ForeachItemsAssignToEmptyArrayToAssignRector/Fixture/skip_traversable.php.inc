<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachItemsAssignToEmptyArrayToAssignRector\Fixture;

class SkipTraversable
{
    public function run()
    {
        $items = [];
        foreach ($this->getTraversable() as $item) {
            $items[] = $item;
        }
    }

    private function getTraversable(): iterable
    {
        yield 123;
    }
}
