<?php

namespace Rector\Tests\Php80\Rector\Identical\StrEndsWithRector\Fixture;

class SkipLooseSubstr
{
    public function run()
    {
        $isMatch = substr($haystack, -strlen($needle)) == $needle;

        $isMatch = $needle == substr($haystack, -strlen($needle));
    }
}

?>
