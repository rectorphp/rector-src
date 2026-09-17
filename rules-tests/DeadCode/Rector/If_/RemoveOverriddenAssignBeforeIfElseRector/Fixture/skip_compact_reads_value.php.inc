<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveOverriddenAssignBeforeIfElseRector\Fixture;

final class SkipCompactReadsValue
{
    public function run(bool $value): array
    {
        $result = 'x';
        if ($value) {
            $data = compact('result');
            $result = [1, 2, 3];

            return $data;
        } else {
            $result = [4, 5, 6];
        }

        return $result;
    }
}
