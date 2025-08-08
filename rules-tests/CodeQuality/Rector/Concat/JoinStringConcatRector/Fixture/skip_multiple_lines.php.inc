<?php

namespace Rector\Tests\CodeQuality\Rector\Concat\JoinStringConcatRector\Fixture;

class SkipMultipleLines
{
    public function run()
    {
        return 'a' .
            'b' .
            'c' .
            'd';
    }
}
