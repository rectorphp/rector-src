<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveDoubleAssignRector\Fixture;

class NextReassignWithCallOnVariable
{
    private $items;

    public function create($input)
    {
        $items = $input;
        $items = $this->getItems();
    }

    public function getItems()
    {
        return sort($this->items);
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveDoubleAssignRector\Fixture;

class NextReassignWithCallOnVariable
{
    private $items;

    public function create($input)
    {
        $items = $this->getItems();
    }

    public function getItems()
    {
        return sort($this->items);
    }
}

?>
