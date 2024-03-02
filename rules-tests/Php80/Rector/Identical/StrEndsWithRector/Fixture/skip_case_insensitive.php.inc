<?php

namespace Rector\Tests\Php80\Rector\Identical\StrEndsWithRector\Fixture;

final class SkipCaseInsensitive
{
    public function run($haystack, $needle)
    {
        return substr_compare($haystack, $needle, -strlen($needle), null, true) === 0;
    }
}
