<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAllRector\Fixture;

class SkipVariableReUseAfterForeach
{
    public function checkAllAnimalsStartWithC(array $animals)
    {
        $found = true;
        foreach ($animals as $animal) {
            if (!str_starts_with($animal, 'c')) {
                $found = false;
                break;
            }
        }

        if (isset($animal)) {
            echo 'hit';
        }

        return $found;
    }
}