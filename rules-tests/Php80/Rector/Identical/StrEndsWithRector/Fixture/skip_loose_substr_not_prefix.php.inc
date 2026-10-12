<?php

namespace Rector\Tests\Php80\Rector\Identical\StrEndsWithRector\Fixture;

class SkipLooseSubstrNotPrefix
{
    public function run()
    {
        $isNotMatch = substr($haystack, -strlen($needle)) != $needle;

        $isNotMatch = $needle != substr($haystack, -strlen($needle));
    }
}

?>
