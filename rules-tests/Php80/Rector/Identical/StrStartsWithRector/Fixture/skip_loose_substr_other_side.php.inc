<?php

namespace Rector\Tests\Php80\Rector\Identical\StrStartsWithRector\Fixture;

class SkipLooseSubstrOtherSide
{
    public function run()
    {
        $isMatch = $needle == substr($haystack, 0, strlen($needle));

        $isNotMatch = $needle != substr($haystack, 0, strlen($needle));
    }
}

?>
