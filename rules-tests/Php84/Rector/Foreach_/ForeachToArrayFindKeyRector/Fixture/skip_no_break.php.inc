<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayFindKeyRector\Fixture;

class SkipNoBreak
{
    public function skipNoBreak($animals)
    {
        $found = null;
        foreach ($animals as $idx => $animal) {
            if (str_starts_with($animal, 'c')) {
                $found = $idx;
            }
        }
        return $found;
    }
}