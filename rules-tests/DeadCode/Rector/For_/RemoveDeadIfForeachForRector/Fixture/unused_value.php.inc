<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadIfForeachForRector\Fixture;

class UnusedValue
{
    public function run()
    {
        $value = 5;
        $values = ['a', 'b', 'c'];

        foreach ($values as $value) {

        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadIfForeachForRector\Fixture;

class UnusedValue
{
    public function run()
    {
        $value = 5;
        $values = ['a', 'b', 'c'];
    }
}

?>
