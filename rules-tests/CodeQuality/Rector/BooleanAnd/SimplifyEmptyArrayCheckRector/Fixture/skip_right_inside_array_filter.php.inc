<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanAnd\SimplifyEmptyArrayCheckRector\Fixture;

final class SkipRightInsideArrayFilter
{
    public function example(mixed $value): bool {
        if (is_array($value) && empty(array_filter($value))) {
            return true;
        }

        return false;
    }
}
