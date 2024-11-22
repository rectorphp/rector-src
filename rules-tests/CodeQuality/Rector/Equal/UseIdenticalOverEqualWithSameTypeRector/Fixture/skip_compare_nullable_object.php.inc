<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

final class SkipCompareNullableObject
{
    public function equal(?\DateTime $d, ?\DateTime $d2)
    {
    	return $d == $d2;
    }
}
