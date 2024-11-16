<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveDoubleAssignRector\Fixture;

class SkipNextReassignWithCallOnPropertyFetch
{
    private $items;

    public function create($input)
    {
        $this->items = $input;

        $this->items = $this->getItems();
    }

    public function getItems()
    {
        return sort($this->items);
    }
}