<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Fixture;

final class ArrayMap
{
    public function call($items)
    {
        array_map(fn($x) => strlen($x), $items);
    }

    public function run(): void
    {
        $array = [1, 2, 3];

        array_map(
            fn($item) => var_dump(
                $item,
            ),
            $array
        );
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Fixture;

final class ArrayMap
{
    public function call($items)
    {
        array_map(strlen(...), $items);
    }

    public function run(): void
    {
        $array = [1, 2, 3];

        array_map(
            var_dump(
                ...
            ),
            $array
        );
    }
}

?>
