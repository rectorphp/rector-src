<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SimplifyRegexPatternRector\Fixture;

class SkipSurplusOpenSquareBracket
{
    public function run()
    {
        $purln = preg_replace("/[^[a-zA-Z0-9_]/", "", "test");
    }
}
