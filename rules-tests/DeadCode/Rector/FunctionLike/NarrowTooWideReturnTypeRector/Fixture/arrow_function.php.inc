<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Fixture;

$simple = fn(int $x): string|int|bool => $x > 5 ? 'high' : 10;

$ternary = fn(?int $value): string|int|float|null =>
    $value === null ? null : ($value > 0 ? 'positive' : -1);

$cast = fn(mixed $input): int|string|array => (int) $input;

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Fixture;

$simple = fn(int $x): string|int => $x > 5 ? 'high' : 10;

$ternary = fn(?int $value): string|int|null =>
    $value === null ? null : ($value > 0 ? 'positive' : -1);

$cast = fn(mixed $input): int => (int) $input;

?>
