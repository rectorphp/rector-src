<?php

namespace Rector\Tests\Php80\Rector\Identical\StrEndsWithRector\Fixture;

final class SkipLooseNumeric
{
    /**
     * @param non-empty-string $needle
     */
    public function run(string $haystack, string $needle)
    {
        $isMatch = substr($haystack, -strlen($needle)) == $needle;

        $isNotMatch = $needle != substr($haystack, -strlen($needle));

        $isMatch = substr($haystack, -3) == '1e1';

        $isNotMatch = '1e1' != substr($haystack, -3);
    }
}
