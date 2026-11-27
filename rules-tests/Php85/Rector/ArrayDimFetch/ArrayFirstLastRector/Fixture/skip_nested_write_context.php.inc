<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector\Fixture;

final class SkipNestedWriteContext
{
    public function run(array $items, array $other)
    {
        ++$items[array_key_last($items)]['k'];
        $items[array_key_first($items)]['k']--;

        unset($items[array_key_last($items)]['k']);
        unset($items[array_key_first($items)]['k']['l']);

        $list = [&$items[array_key_last($items)]];
        $nestedList = [&$items[array_key_first($items)]['k']];
        $ref = &$items[array_key_last($items)]['k'];

        sort($items[array_key_first($items)]['k']);

        foreach ($items[array_key_last($items)]['k'] as &$item) {
        }

        foreach ($other as $items[array_key_last($items)]) {
        }

        foreach ($other as $items[array_key_first($items)] => $value) {
        }

        foreach ($other as [$items[array_key_last($items)]['k']]) {
        }

        $fn = fn &() => $items[array_key_last($items)];
    }

    public function &byRefReturn(array $items)
    {
        return $items[array_key_last($items)]['k'];
    }
}
