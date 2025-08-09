<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAnyRector\Fixture;

class SkipVariableReUseAfterForeach
{
    public function checkAnimal(array $animals)
    {
        $found = false;
        foreach ($animals as $animal) {
            if (str_starts_with($animal, 'c')) {
                $found = true;
                break;
            }
        }

        if (isset($animal)) {
            echo 'hit';
        }

        return $found;
    }
}
