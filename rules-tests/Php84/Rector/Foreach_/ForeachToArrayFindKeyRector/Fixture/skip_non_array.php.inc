<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayFindKeyRector\Fixture;

class SkipNonArray
{
    public function findAnimalKey(\ArrayIterator $animals)
    {
        $found = null;
        foreach ($animals as $idx => $animal) {
            if (str_starts_with($animal, 'c')) {
                $found = $idx;
                break;
            }
        }
        return $found;
    }
}
