<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveOverriddenAssignBeforeIfElseRector\Fixture;

final class SkipReadInOverride
{
    public function run(bool $value): array
    {
        $result = [1];
        if ($value) {
            $result = array_merge($result, [2]);
        } else {
            $result = [4, 5, 6];
        }

        return $result;
    }
}
