<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

final class PropertyForeach
{
    private array $items = [];

    public function run()
    {
        if ($this->items) {
            foreach ($this->items as $item) {
            }
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

final class PropertyForeach
{
    private array $items = [];

    public function run()
    {
        foreach ($this->items as $item) {
        }
    }
}

?>
