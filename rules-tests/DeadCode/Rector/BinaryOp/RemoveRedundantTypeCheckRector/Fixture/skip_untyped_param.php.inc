<?php

namespace Rector\Tests\DeadCode\Rector\BinaryOp\RemoveRedundantTypeCheckRector\Fixture;

final class SkipUntypedParam
{
    public function run($value): void
    {
        if (null === $value || ! is_string($value)) {
            return;
        }

        echo $value;
    }
}
