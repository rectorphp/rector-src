<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

/**
 * @see https://3v4l.org/qU0Ou
 */
class SkipObjectsInsideArray
{
    public function run(\stdClass $firstValue, \stdClass $secondValue)
    {
        return [$firstValue] == [$secondValue];
    }
}
