<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanAnd\SimplifyEmptyArrayCheckRector\Fixture;

final class SkipDifferentArrayFilterValue
{
    public function functionCallInsideEmpty($a, $values): bool
    {
        return is_array($a) && empty(array_filter($values));
    }
}
