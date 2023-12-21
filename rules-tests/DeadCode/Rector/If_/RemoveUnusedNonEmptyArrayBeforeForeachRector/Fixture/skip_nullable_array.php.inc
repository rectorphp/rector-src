<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

class SkipNullableArray
{
    public function run(?array $values) {
        if (empty($values)) {
            return;
        }

        foreach ($values as $value) {
            echo $value;
        }
    }
}

?>

