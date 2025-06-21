<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAllRector\Fixture;

class SkipNoBreak
{
    public function checkWithoutBreak($animals)
    {
        $found = true;
        foreach ($animals as $animal) {
            if (!str_starts_with($animal, 'c')) {
                $found = false;
            }
        }
        return $found;
    }
}
