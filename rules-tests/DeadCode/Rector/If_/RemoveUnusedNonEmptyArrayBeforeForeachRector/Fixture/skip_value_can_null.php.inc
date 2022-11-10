<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector\Fixture;

class SkipPropertyNull
{
    private $items = null;

    public function run()
    {
        if ($this->items) {
            foreach ($this->items as $value) {
                echo $value;
            }
        }
    }
}
