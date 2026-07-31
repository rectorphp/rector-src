<?php

namespace Rector\Tests\Php73\Rector\FuncCall\ArrayKeysToArrayKeyFirstLastRector\Fixture;

final class SkipArrayKeysWithSearchValue
{
    public function run(array $items)
    {
        // array_keys() with a search value returns only the matching keys
        $firstMatchingKey = current(array_keys($items, 'value'));
        $lastMatchingKey = end(array_keys($items, 'value', true));

        return [$firstMatchingKey, $lastMatchingKey];
    }
}
