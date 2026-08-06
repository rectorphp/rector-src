<?php

namespace Rector\Tests\DeadCode\Rector\BinaryOp\RemoveRedundantTypeCheckRector\Fixture;

final class SkipTruthyCheckOnUnion
{
    public function run(array|string $value): void
    {
        if ($value && is_array($value)) {
            return;
        }
    }
}
