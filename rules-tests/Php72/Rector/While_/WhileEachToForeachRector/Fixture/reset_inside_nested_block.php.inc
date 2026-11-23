<?php

namespace Rector\Tests\Php72\Rector\While_\WhileEachToForeachRector\Fixture;

final class ResetInsideNestedBlock
{
    public function run(array $items, bool $flag)
    {
        if ($flag) {
            reset($items);
            while (list($key, $item) = each($items)) {
                echo $key . $item;
            }
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php72\Rector\While_\WhileEachToForeachRector\Fixture;

final class ResetInsideNestedBlock
{
    public function run(array $items, bool $flag)
    {
        if ($flag) {
            reset($items);
            foreach ($items as $key => $item) {
                echo $key . $item;
            }
        }
    }
}

?>
