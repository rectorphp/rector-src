<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

class SkipCountDifferentVariable
{
    public function run(array $vars, array $vars2)
    {
        if (count($vars) > 0) {
            foreach ($vars2 as $key => $val) {
                echo "hello";
            }
        }

        return [];
    }
}