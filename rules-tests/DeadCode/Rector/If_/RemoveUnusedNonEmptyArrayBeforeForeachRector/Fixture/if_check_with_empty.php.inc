<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

final class IfCheckWithEmpty
{
    public function run(array $items)
    {
        if (empty($items)) {
            return;
        }

        foreach ($items as $item) {
            echo $item;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

final class IfCheckWithEmpty
{
    public function run(array $items)
    {
        foreach ($items as $item) {
            echo $item;
        }
    }
}

?>
