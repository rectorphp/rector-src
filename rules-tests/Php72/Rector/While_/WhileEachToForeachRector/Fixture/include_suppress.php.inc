<?php

namespace Rector\Tests\Php72\Rector\While_\WhileEachToForeachRector\Fixture;

final class IncludeSuppress
{
    public function run(array $items)
    {
        reset($items);
        while (list(, $item) = @each($items)) {
            // some code
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php72\Rector\While_\WhileEachToForeachRector\Fixture;

final class IncludeSuppress
{
    public function run(array $items)
    {
        reset($items);
        foreach ($items as $item) {
            // some code
        }
    }
}

?>
