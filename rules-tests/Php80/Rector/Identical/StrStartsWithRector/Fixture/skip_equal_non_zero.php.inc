<?php

namespace Rector\Tests\Php80\Rector\Identical\StrStartsWithRector\Fixture;

class SkipEqualNonZero
{
    public function run()
    {
        $isMatch = substr($haystack, 1, strlen($needle)) == $needle;
    }
}
