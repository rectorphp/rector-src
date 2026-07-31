<?php

namespace Rector\Tests\Php73\Rector\FuncCall\ArrayKeysToArrayKeyFirstLastRector\Fixture;

final class SkipOtherCalls
{
    public function run(array $items, array $keys)
    {
        // not array_keys()
        $first = current(array_values($items));

        // no inner call at all
        $stillFirst = current($keys);

        // key() of the keys array is always 0, not the first key
        $zero = key(array_keys($items));

        // first class callable
        $callable = current(...);

        // spread
        $spread = current(...array_keys($items));

        return [$first, $stillFirst, $zero, $callable, $spread];
    }
}
