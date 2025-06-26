<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayFindRector\Fixture;

class SkipMultipleStatements
{
    public function findAnimal(array $animals)
    {
        $found = null;
        foreach ($animals as $animal) {
            if (str_starts_with($animal, 'c')) {
                echo "Found: " . $animal;
                $found = $animal;
                break;
            }
        }
        return $found;
    }
}