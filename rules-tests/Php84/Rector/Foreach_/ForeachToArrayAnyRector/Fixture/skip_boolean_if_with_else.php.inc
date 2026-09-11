<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAnyRector\Fixture;

class SkipBooleanIfWithElse
{
    public function run(array $animals)
    {
        $found = false;
        foreach ($animals as $animal) {
            if (str_starts_with($animal, 'c')) {
                $found = true;
                break;
            } else {
                $found = false;
            }
        }
        return $found;
    }
}
