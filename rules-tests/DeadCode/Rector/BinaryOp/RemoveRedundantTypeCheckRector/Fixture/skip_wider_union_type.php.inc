<?php

namespace Rector\Tests\DeadCode\Rector\BinaryOp\RemoveRedundantTypeCheckRector\Fixture;

final class SkipWiderUnionType
{
    public function run(string|int|null $value): void
    {
        if (null === $value || ! is_string($value)) {
            return;
        }

        echo $value;
    }
}
