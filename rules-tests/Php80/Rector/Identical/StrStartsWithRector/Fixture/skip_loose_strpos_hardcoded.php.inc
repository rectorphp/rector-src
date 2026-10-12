<?php

namespace Rector\Tests\Php80\Rector\Identical\StrStartsWithRector\Fixture;

class SkipLooseStrposHardcoded
{
    public function run()
    {
        $isMatch = strpos($haystack, 'hardcoded') == 0;
    }
}

?>
