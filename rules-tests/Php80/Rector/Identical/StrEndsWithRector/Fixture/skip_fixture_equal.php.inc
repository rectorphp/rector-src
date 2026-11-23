<?php

namespace Rector\Tests\Php80\Rector\Identical\StrEndsWithRector\Fixture;

class SkipFixtureEqual
{
    public function run()
    {
        $isMatch = substr($haystack, -strlen($needle)) == $needle;

        $isMatch = $needle == substr($haystack, -strlen($needle));
    }
}
