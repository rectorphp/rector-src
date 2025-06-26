<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAnyRector\Fixture;

class SkipNoBreak
{
    public function run(array $animals)
    {
        $found = false;
        foreach ($animals as $animal) {
            if (str_starts_with($animal, 'c')) {
                $found = true;
            }
        }
        return $found;
    }
}