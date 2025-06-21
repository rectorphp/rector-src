<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAnyRector\Fixture;

class SkipMultipleStatements
{
    public function run($animals)
    {
        $found = false;
        foreach ($animals as $animal) {
            if (str_starts_with($animal, 'c')) {
                $found = true;
                echo 'Found: ' . $animal;
                break;
            }
        }
        return $found;
    }
}