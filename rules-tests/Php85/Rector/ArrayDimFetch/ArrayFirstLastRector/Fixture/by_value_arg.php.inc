<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector\Fixture;

final class ByValueArg
{
    public function run(array $items)
    {
        echo count($items[array_key_first($items)]);

        foreach ($items[array_key_last($items)] as $item) {
        }
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector\Fixture;

final class ByValueArg
{
    public function run(array $items)
    {
        echo count(array_first($items));

        foreach (array_last($items) as $item) {
        }
    }
}

?>
