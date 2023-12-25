<?php

namespace Rector\Tests\Php80\Rector\Identical\StrStartsWithRector\FixturePhp74;

class SkipSubstr
{
    public function run()
    {
        $isMatch = substr($haystack, 0, strlen($needle)) === $needle;
    }
}

