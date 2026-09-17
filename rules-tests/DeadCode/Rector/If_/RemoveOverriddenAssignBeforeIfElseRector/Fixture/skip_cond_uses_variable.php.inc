<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveOverriddenAssignBeforeIfElseRector\Fixture;

final class SkipCondUsesVariable
{
    public function run(): array
    {
        $result = [1];
        if ($result !== []) {
            $result = [1, 2, 3];
        } else {
            $result = [4, 5, 6];
        }

        return $result;
    }
}
