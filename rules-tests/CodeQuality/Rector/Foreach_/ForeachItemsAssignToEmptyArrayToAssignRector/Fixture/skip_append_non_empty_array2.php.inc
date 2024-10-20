<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachItemsAssignToEmptyArrayToAssignRector\Fixture;

class SkipAppendNonEmptyArray2
{
    public function run()
    {
        $b = ['b'];
        $c = [];

        // assign
        $c = ['non empty array'];

        // append after assign
        foreach ($b as $bx) {
            $c[] = $bx;
        }
    }
}
