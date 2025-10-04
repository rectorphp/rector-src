<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

class SkipObjectsInsideArray
{
    public function run(\stdClass $firstValue, \stdClass $secondValue)
    {
        return [$firstValue] == [$secondValue];
    }
}
