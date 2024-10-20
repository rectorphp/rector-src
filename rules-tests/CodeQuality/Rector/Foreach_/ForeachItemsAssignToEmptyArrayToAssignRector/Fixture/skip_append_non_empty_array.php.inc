<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachItemsAssignToEmptyArrayToAssignRector\Fixture;

class SkipAppendNonEmptyArray
{
    public function run()
    {
        $a = ['a'];
        $b = ['b'];
        $c = [];

        // assign
        $c = $a;

        // append after assign
        foreach ($b as $bx) {
            $c[] = $bx;
        }
    }
}
