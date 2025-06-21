<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAllRector\Fixture;

class SkipNoBooleanInitialization
{
    public function checkWithoutBooleanInit($animals)
    {
        $found = 'initial';
        foreach ($animals as $animal) {
            if (!str_starts_with($animal, 'c')) {
                $found = false;
                break;
            }
        }
        return $found;
    }

    public function checkWithFalseInit($animals)
    {
        $found = false;
        foreach ($animals as $animal) {
            if (!str_starts_with($animal, 'c')) {
                $found = false;
                break;
            }
        }
        return $found;
    }
}
