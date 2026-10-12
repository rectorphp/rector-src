<?php

namespace Rector\Tests\Php80\Rector\Identical\StrStartsWithRector\Fixture;

class SkipLooseStrpos
{
    public function run()
    {
        $isMatch = strpos($haystack, $needle) == 0;
    }
}

?>
