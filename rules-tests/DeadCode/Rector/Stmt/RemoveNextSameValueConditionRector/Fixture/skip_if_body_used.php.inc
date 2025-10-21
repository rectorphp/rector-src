<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveNextSameValueConditionRector\Fixture;

final class SkipIfBodyUsed
{
    public function __construct(array $items)
    {
        $count = 100;
        if ($items === []) {
            $items = ['a'];
        }

        if ($items === []) {
            return $count;
        }
    }
}
