<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

final class IfNonEmptyAndBiggerThanZero
{
    public function run(array $items)
    {
        if ($items && count($items) > 0) {
            foreach ($items as $item) {
                echo $item;
            }
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

final class IfNonEmptyAndBiggerThanZero
{
    public function run(array $items)
    {
        foreach ($items as $item) {
            echo $item;
        }
    }
}

?>
