<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Fixture;

function makeInstance(string $className, array $options = []): object
{
    return new $className(...$options);
}

// This works - closure ignores the key (2nd parameter from array_map)
$result1 = array_map(
    static fn(string $className): object => makeInstance($className),
    $items
);

// This FAILS - first-class callable receives BOTH value and key
// array_map passes: $className (string) and $key (int)
// But makeInstance expects: $className (string) and $options (array)
$result2 = array_map(makeInstance(...), $items, array_keys($items));
// TypeError: makeInstance(): Argument #2 ($options) must be of type array, int give