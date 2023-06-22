<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SimplifyRegexPatternRector\Fixture;

class SkipNotRegex
{
    public function run($value)
    {
        echo '[0-9] and \d are a bit different.';
    }
}
