<?php

namespace Rector\Tests\PhpParser\Printer\Fixture;

$result = array_map(array: [1, 2, 3], callback: fn(int $value) => $value);
