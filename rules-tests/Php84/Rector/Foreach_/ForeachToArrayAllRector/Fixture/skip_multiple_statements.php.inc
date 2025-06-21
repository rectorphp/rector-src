<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAllRector\Fixture;

class SkipMultipleStatements
{
    public function checkWithMultipleStatements($animals)
    {
        $found = true;
        foreach ($animals as $animal) {
            if (!str_starts_with($animal, 'c')) {
                echo $animal;
                $found = false;
                break;
            }
        }
        return $found;
    }
}
