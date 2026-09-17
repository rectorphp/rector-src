<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveOverriddenAssignBeforeIfElseRector\Fixture;

final class SkipPartialOverride
{
    public function run(bool $value): array
    {
        $result = [];
        if ($value) {
            $result[] = 1;
        } else {
            $result = [4, 5, 6];
        }

        return $result;
    }
}
