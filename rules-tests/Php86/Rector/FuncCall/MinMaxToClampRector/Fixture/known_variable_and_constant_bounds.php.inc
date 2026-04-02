<?php

namespace Rector\Tests\Php86\Rector\FuncCall\MinMaxToClampRector\Fixture;

$limit = 100;

fn (int $value) => max(0, min(100, $value));
fn (int $value) => max(0, min($limit, $value));
fn (int $value) => max(0, min($value, $limit));
fn (int $value) => max(PHP_INT_MIN, min(PHP_INT_MAX, $value));
fn (string $value) => max('a', min('z', $value));

?>
-----
<?php

namespace Rector\Tests\Php86\Rector\FuncCall\MinMaxToClampRector\Fixture;

$limit = 100;

fn (int $value) => clamp($value, 0, 100);
fn (int $value) => clamp($value, 0, $limit);
fn (int $value) => clamp($value, 0, $limit);
fn (int $value) => clamp($value, PHP_INT_MIN, PHP_INT_MAX);
fn (string $value) => clamp($value, 'a', 'z');

?>
