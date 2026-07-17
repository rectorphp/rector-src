<?php

namespace Rector\Tests\Php85\Rector\Expression\NestedFuncCallsToPipeOperatorRector\FixtureMinimumDepth;

final class SkipBelowMinimumDepth
{
    public function run(array $input)
    {
        assert(is_string($input['email']));
        $normalizedName = trim(strtolower($input['name']));
    }
}
