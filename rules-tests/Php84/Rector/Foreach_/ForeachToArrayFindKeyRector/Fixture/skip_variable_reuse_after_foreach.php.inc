<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayFindKeyRector\Fixture;

class SkipVariableReUseAfterForeach
{
    public function findAnimalKey(array $animals)
    {
        $found = null;
        foreach ($animals as $idx => $animal) {
            if (str_starts_with($animal, 'c')) {
                $found = $idx;
                break;
            }
        }

        if (isset($animal)) {
            echo 'hit';
        }

        return $found;
    }
}