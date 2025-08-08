<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAnyCombinedRector\Fixture;

class SkipNoBooleanInitialization
{
    public function run(array $animals)
    {
        $found = null;
        foreach ($animals as $animal) {
            if (str_starts_with($animal, 'c')) {
                $found = true;
                break;
            }
        }
        return $found;
    }
}
