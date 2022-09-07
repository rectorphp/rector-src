<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

$values = [];
if ($keys = []) {
    foreach ($values as $value) {
        echo array_search($value, $keys, true);
    }
}

