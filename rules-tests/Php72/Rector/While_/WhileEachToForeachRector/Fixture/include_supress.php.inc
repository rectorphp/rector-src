<?php

namespace Rector\Tests\Php72\Rector\While_\WhileEachToForeachRector\Fixture;

final class IncludeSupress
{
    public function run(array $items)
    {
        while (list(, $item) = @each($items)) {
            // some code
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php72\Rector\While_\WhileEachToForeachRector\Fixture;

final class IncludeSupress
{
    public function run(array $items)
    {
        foreach ($items as $item) {
            // some code
        }
    }
}

?>
