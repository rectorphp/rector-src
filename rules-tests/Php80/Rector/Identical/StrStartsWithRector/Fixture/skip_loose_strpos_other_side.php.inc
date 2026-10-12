<?php

namespace Rector\Tests\Php80\Rector\Identical\StrStartsWithRector\Fixture;

class SkipLooseStrposOtherSide
{
    public function run()
    {
        $isMatch = 0 == strpos($haystack, $needle);
    }
}

?>
