<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachWithReturnToArrayAnyRector\Fixture;

class SkipMultipleStatements
{
    public function run(array $animals)
    {
        foreach ($animals as $animal) {
            if (str_starts_with($animal, 'c')) {
                echo 'Found: ' . $animal;
                return true;
            }
        }
        return false;
    }
}
