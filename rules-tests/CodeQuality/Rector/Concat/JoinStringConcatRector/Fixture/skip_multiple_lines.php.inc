<?php

namespace Rector\Tests\CodeQuality\Rector\Concat\JoinStringConcatRector\Fixture;

class SkipMultiplelines
{
    public function run()
    {
        return 'a' .
            'b' .
            'c' .
            'd';
    }
}
