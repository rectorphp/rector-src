<?php

namespace Rector\Tests\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector\Fixture;

final class SkipNegationClassConstFetch
{
    protected const CSRF_HASH_BYTES      = 16;

    protected function derandomize(string $token): int
    {
        echo -static::CSRF_HASH_BYTES * 2;
    }
}
