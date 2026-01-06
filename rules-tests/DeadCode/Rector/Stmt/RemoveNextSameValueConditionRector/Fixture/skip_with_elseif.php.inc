<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveNextSameValueConditionRector\Fixture;

final class SkipWithElseif
{
    public function __construct(array $items)
    {
        $count = 100;
        if ($items === []) {
            $count = 0;
        }

        if ($items === []) {
            return $count;
        } else {
            return 'test';
        }
    }
}
