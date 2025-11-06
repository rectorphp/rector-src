<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Fixture;

$simple = fn($x): string|int|bool => $x > 5 ? 'high' : 10;

$ternary = fn($value): string|int|float|null =>
    $value === null ? null : ($value > 0 ? 'positive' : -1);

$cast = fn($input): int|string|array => (int) $input;

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector\Fixture;

$simple = fn($x): string|int => $x > 5 ? 'high' : 10;

$ternary = fn($value): string|int|null =>
    $value === null ? null : ($value > 0 ? 'positive' : -1);

$cast = fn($input): int => (int) $input;

?>
