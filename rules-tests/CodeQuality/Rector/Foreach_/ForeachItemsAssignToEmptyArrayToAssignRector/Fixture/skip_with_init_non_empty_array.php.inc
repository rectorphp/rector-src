<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachItemsAssignToEmptyArrayToAssignRector\Fixture;

use stdClass;

class SkipWithInitNonEmptyArray
{
    public function run($items)
    {
        $primary = new stdClass();

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
