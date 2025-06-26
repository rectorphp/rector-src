<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayFindKeyRector\Fixture;

class SkipMultipleStatements
{
    public function skipMultipleStatements(array $animals)
    {
        $found = null;
        foreach ($animals as $idx => $animal) {
            if (str_starts_with($animal, 'c')) {
                $found = $idx;
                echo "Found: " . $animal;
                break;
            }
        }
        return $found;
    }
}