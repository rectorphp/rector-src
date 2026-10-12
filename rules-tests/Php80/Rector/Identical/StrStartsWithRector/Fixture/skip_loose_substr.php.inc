<?php

namespace Rector\Tests\Php80\Rector\Identical\StrStartsWithRector\Fixture;

class SkipLooseSubstr
{
    public function run()
    {
        $isMatch = substr($haystack, 0, strlen($needle)) == $needle;
    }
}

?>
