<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveConditionExactReturnRector\Fixture;

final class SkipDifferentReturn
{
    public function run(array $result)
    {
        if ($result === []) {
            return [];
        }

        return 500;
    }
}
