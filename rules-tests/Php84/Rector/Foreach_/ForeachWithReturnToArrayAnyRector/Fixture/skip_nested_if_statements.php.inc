<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachWithReturnToArrayAnyRector\Fixture;

class SkipMultipleStatements
{
    public function run(array $animals)
    {
        foreach ($animals as $animal) {
            if(str_starts_with($animal, 'foo')) {
                if (str_starts_with($animal, 'c')) {
                    return true;
                }
            }
        }
        return false;
    }
}
