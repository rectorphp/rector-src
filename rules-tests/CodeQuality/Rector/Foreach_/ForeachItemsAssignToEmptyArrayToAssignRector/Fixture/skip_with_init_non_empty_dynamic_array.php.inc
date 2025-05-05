<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachItemsAssignToEmptyArrayToAssignRector\Fixture;

use stdClass;

class SkipWithInitNonEmptyDynamicArray
{
    public function run($items, stdClass $primary)
    {
        // init
        $assembled = [
            $primary,
        ];

        // to append
        $appends = [
            new stdClass(),
            new stdClass(),
        ];

        foreach ($appends as $secondary) {
            $assembled[] = $secondary;
        }

        return $assembled;
    }
}
