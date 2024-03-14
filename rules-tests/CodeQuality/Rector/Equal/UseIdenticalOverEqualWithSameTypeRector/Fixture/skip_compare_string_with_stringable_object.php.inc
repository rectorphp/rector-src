<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

class SkipCompareStringWithStringableObject
{
    public function run()
    {
        $obj = new class {
            public function __toString()
            {
                return 'a';
            }
        };

        return 'a' == $obj;
    }
}
