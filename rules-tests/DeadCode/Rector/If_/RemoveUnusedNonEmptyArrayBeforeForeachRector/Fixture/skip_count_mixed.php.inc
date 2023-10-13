<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

class SkipCountMixed
{
    public function run($vars)
    {
        if (count($vars) > 0) {
            foreach ($vars as $key => $val) {
                echo "hello";
            }
        }

        return [];
    }
}