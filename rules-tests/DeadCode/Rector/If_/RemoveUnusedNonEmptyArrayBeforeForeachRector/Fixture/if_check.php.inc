<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

class IfCheck
{
    public function run()
    {
        $values = [];
        if ($values) {
            foreach ($values as $value) {
                echo $value;
            }
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

class IfCheck
{
    public function run()
    {
        $values = [];
        foreach ($values as $value) {
            echo $value;
        }
    }
}

?>
