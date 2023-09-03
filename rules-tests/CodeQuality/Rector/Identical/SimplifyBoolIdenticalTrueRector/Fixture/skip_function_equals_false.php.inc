<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\SimplifyBoolIdenticalTrueRector\Fixture;

final class SkipFunctionEqualsFalse
{
    public function run($value, array $items)
    {
        $isMatch = in_array($value, $items, TRUE) === FALSE;
    }
}
