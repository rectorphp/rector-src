<?php

namespace Rector\Tests\CodeQuality\Rector\For_\ForRepeatedCountToOwnVariableRector\Fixture;

class MethodCallCount
{
    public function run($items, \stdClass $someObject)
    {
        for ($i = 5; $i <= count($someObject->getItems()); $i++) {
            echo $items[$i];
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\For_\ForRepeatedCountToOwnVariableRector\Fixture;

class MethodCallCount
{
    public function run($items, \stdClass $someObject)
    {
        $counter = count($someObject->getItems());
        for ($i = 5; $i <= $counter; $i++) {
            echo $items[$i];
        }
    }
}

?>
