<?php

namespace Rector\Tests\CodeQuality\Rector\For_\ForRepeatedCountToOwnVariableRector\Fixture;

class FallbackForComplex
{
    public function run($items, \stdClass $someObject)
    {
        for ($i = 5; $i <= count($someObject->getItems() + 10); $i++) {
            echo $items[$i];
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\For_\ForRepeatedCountToOwnVariableRector\Fixture;

class FallbackForComplex
{
    public function run($items, \stdClass $someObject)
    {
        $counter = count($someObject->getItems() + 10);
        for ($i = 5; $i <= $counter; $i++) {
            echo $items[$i];
        }
    }
}

?>
