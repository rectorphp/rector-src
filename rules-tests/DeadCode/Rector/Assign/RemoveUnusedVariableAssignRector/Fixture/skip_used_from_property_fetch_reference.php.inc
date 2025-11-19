<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

function SkipUsedFromPropertyFetchReference(\stdClass $target, array $path, mixed $value): void {
    $current = $target;
    foreach ($path as $key) {
      $current = &$current->{$key};
    }
    $current = $value;
}

?>
