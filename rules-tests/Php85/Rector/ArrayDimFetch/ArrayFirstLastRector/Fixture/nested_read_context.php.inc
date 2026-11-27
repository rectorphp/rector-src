<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector\Fixture;

final class NestedReadContext
{
    public function run(array $items)
    {
        echo $items[array_key_last($items)]['k'];
        echo count($items[array_key_first($items)]['k']);

        foreach ($items[array_key_last($items)]['k'] as $item) {
        }

        $fn = fn () => $items[array_key_first($items)];
    }

    public function byValueReturn(array $items)
    {
        return $items[array_key_last($items)]['k'];
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector\Fixture;

final class NestedReadContext
{
    public function run(array $items)
    {
        echo array_last($items)['k'];
        echo count(array_first($items)['k']);

        foreach (array_last($items)['k'] as $item) {
        }

        $fn = fn () => array_first($items);
    }

    public function byValueReturn(array $items)
    {
        return array_last($items)['k'];
    }
}

?>
