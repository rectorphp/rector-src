<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadLoopRector\Fixture;

final class SkipForeachNonArray
{
    public function run(\Generator $generator, iterable $iterable, $mixed)
    {
        foreach ($generator as $value) {
        }

        foreach ($iterable as $value) {
        }

        foreach ($mixed as $value) {
        }
    }
}
