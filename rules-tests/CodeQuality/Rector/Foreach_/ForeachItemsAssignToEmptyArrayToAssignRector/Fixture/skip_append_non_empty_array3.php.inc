<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachItemsAssignToEmptyArrayToAssignRector\Fixture;

class SkipAppendPossibleNonEmptyFromParam
{
    public function run(array $init = [])
    {
        $b = ['b'];
        $c = [];

        // assign
        $c = $init;

        // append after assign
        foreach ($b as $bx) {
            $c[] = $bx;
        }
    }
}
