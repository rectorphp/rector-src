<?php

namespace Rector\Tests\Php80\Rector\Identical\StrEndsWithRector\Fixture;

final class SkipEmptyNeedle
{
    public function run(string $haystack, string $needle)
    {
        $isMatch = substr($haystack, -strlen($needle)) === $needle;

        $isNotMatch = $needle !== substr($haystack, -strlen($needle));

        $isMatch = substr_compare($haystack, $needle, -strlen($needle)) === 0;

        $isNotMatch = 0 != substr_compare($haystack, $needle, -strlen($needle));

        $isMatch = substr($haystack, -0) === '';

        $isMatch = substr_compare($haystack, '', -0) === 0;
    }
}
