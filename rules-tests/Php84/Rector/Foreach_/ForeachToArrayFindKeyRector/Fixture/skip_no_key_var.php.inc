<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayFindKeyRector\Fixture;

class SkipNoKeyVar
{
    public function skipNoKeyVar($animals)
    {
        $found = null;
        foreach ($animals as $animal) {
            if (str_starts_with($animal, 'c')) {
                $found = $animal;
                break;
            }
        }
        return $found;
    }
}