<?php

namespace Rector\Tests\CodeQuality\Rector\Concat\JoinStringConcatRector\Fixture;

class SkipMultipleNewline
{
    public function run()
    {
        return 'Hi' . "\n\n";
    }
}
