<?php

namespace Rector\Tests\DeadCode\Rector\Closure\RemoveUnusedClosureVariableUseRector\Fixture;

/**
 * @see https://3v4l.org/9vhce
 */
final class SkipUsedViaCompact
{
    public function run($value)
    {
        return function () use ($value) {
            $v = compact('value');

            return $v;
        };
    }
}
