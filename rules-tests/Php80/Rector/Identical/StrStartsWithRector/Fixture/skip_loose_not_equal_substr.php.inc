<?php

namespace Rector\Tests\Php80\Rector\Identical\StrStartsWithRector\Fixture;

class SkipLooseNotEqualSubstr
{
    public function run()
    {
        $isNotMatch = substr($haystack, 0, strlen($needle)) != $needle;
    }
}

?>
