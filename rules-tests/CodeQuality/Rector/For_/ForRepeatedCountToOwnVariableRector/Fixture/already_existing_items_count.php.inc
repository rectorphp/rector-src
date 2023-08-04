<?php

namespace Rector\Tests\CodeQuality\Rector\For_\ForRepeatedCountToOwnVariableRector\Fixture;

class AlreadyExistingItemsCount
{
    public function run($items, \stdClass $someObject)
    {
        $itemsCount = 500000;

        for ($i = 5; $i <= count($someObject->getItems() + 10); $i++) {
            echo $items[$i];
        }

        return $itemsCount;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\For_\ForRepeatedCountToOwnVariableRector\Fixture;

class AlreadyExistingItemsCount
{
    public function run($items, \stdClass $someObject)
    {
        $itemsCount = 500000;
        $counter = count($someObject->getItems() + 10);

        for ($i = 5; $i <= $counter; $i++) {
            echo $items[$i];
        }

        return $itemsCount;
    }
}

?>
