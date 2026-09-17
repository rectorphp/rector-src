<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveOverriddenAssignBeforeIfElseRector\Fixture;

final class SkipBranchWithoutOverride
{
    public function run(bool $value): array
    {
        $result = [];
        if ($value) {
            $result = [1, 2, 3];
        } else {
            echo 'nothing';
        }

        return $result;
    }
}
