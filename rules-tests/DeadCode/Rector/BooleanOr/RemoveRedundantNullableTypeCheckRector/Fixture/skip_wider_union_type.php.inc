<?php

namespace Rector\Tests\DeadCode\Rector\BooleanOr\RemoveRedundantNullableTypeCheckRector\Fixture;

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
