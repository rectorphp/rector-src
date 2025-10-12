<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveConditionExactReturnRector\Fixture;

final class SkipSideEffectNativeFunction
{
    public function run($result)
    {
        if ($result === array_push($result, 100)) {
            return array_push($result, 100);
        }

        return $result;
    }
}
