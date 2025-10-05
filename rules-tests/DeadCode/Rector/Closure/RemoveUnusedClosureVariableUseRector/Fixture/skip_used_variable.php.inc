<?php

namespace Rector\Tests\DeadCode\Rector\Closure\RemoveUnusedClosureVariableUseRector\Fixture;

final class SkipUsedVariable
{
    public function run($value)
    {
        return function () use ($value) {
            $result = $value + 100;

            return $result;
        };
    }
}
