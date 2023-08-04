<?php

namespace Rector\Tests\CodeQuality\Rector\For_\ForRepeatedCountToOwnVariableRector\Fixture;

class Fixture
{
    public function run($items)
    {
        for ($i = 5; $i <= count($items); $i++) {
            echo $items[$i];
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\For_\ForRepeatedCountToOwnVariableRector\Fixture;

class Fixture
{
    public function run($items)
    {
        $counter = count($items);
        for ($i = 5; $i <= $counter; $i++) {
            echo $items[$i];
        }
    }
}

?>
