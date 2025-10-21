<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveNextSameValueConditionRector\Fixture;

final class SkipIfStmtsBetween
{
    public function __construct(array $items)
    {
        $count = 100;
        if ($items === []) {
            $count = 0;
        }

        $items = ['a'];

        if ($items === []) {
            return $count;
        }
    }
}
