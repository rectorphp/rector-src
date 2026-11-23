<?php

namespace Rector\Tests\Php86\Rector\FuncCall\MinMaxToClampRector\Fixture;

fn (int $value) => max(0, min(100, $value));
fn (int $value) => max(-10, min(10 * 2, $value));
fn (int $value) => max(PHP_INT_MIN, min(PHP_INT_MAX, $value));
fn (string $value) => max('a', min('z', $value));
fn (int $value) => max(5, min(5, $value));

?>
-----
<?php

namespace Rector\Tests\Php86\Rector\FuncCall\MinMaxToClampRector\Fixture;

fn (int $value) => clamp($value, 0, 100);
fn (int $value) => clamp($value, -10, 10 * 2);
fn (int $value) => clamp($value, PHP_INT_MIN, PHP_INT_MAX);
fn (string $value) => clamp($value, 'a', 'z');
fn (int $value) => clamp($value, 5, 5);

?>
