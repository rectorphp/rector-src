<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SimplifyRegexPatternRector\Fixture;

use Nette\Utils\Strings;

class SkipDirectSquareBracketAsDelimiter
{
    public function run($value)
    {
        preg_match('[0-9]', $value);
    }
}
