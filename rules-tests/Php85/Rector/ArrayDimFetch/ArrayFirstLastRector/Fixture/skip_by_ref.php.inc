<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector\Fixture;

final class SkipByRef
{
    public function run(array $items)
    {
        sort($items[array_key_first($items)]);
        array_push($items[array_key_last($items)], 1);
        $this->modify($items[array_key_last($items)]);
        self::modifyStatic($items[array_key_first($items)]);
        self::modifyStatic(value: $items[array_key_last($items)]);
        array_push($items[array_key_first($items)], ...[1, 2]);

        foreach ($items[array_key_last($items)] as &$item) {
        }
    }

    private function modify(array &$value): void
    {
    }

    private static function modifyStatic(array &$value): void
    {
    }
}
