<?php

namespace Rector\Tests\DeadCode\Rector\Stmt\RemoveConditionExactReturnRector\Fixture;

final class SkipSideEffectCall
{
    public function run($result)
    {
        if ($result === $this->isMissing(100)) {
            return $this->isMissing(100);
        }

        return $result;
    }

    private function isMissing(int $int): int
    {
        return mt_rand(0, 1) * $int;
    }
}
