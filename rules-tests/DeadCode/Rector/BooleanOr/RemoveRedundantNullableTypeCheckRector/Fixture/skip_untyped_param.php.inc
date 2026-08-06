<?php

namespace Rector\Tests\DeadCode\Rector\BooleanOr\RemoveRedundantNullableTypeCheckRector\Fixture;

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
