<?php

namespace Rector\Tests\Php73\Rector\FuncCall\ArrayKeysToArrayKeyFirstLastRector\Fixture;

final class SkipPossiblyEmptyArray
{
    public function run(array $items)
    {
        // on an empty array, current/reset/end() return false, while array_key_first/last() return null
        $firstKey = current(array_keys($items));
        $alsoFirstKey = reset(array_keys($items));
        $lastKey = end(array_keys($items));

        return [$firstKey, $alsoFirstKey, $lastKey];
    }
}
