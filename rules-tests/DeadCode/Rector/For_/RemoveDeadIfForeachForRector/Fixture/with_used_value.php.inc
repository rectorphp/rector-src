<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadIfForeachForRector\Fixture;

class WithUsedValue
{
    public function run($someObject, $differentValue)
    {
        $value = 5;
        if ($value) {
        }

        if ($someObject->run()) {
        }

        $values = ['a', 'b', 'c'];
        foreach ($values as $value) {
        }

        return $value;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadIfForeachForRector\Fixture;

class WithUsedValue
{
    public function run($someObject, $differentValue)
    {
        $value = 5;

        if ($someObject->run()) {
        }

        $values = ['a', 'b', 'c'];
        foreach ($values as $value) {
        }

        return $value;
    }
}

?>
