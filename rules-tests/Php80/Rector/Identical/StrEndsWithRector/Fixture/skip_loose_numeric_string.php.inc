<?php

namespace Rector\Tests\Php80\Rector\Identical\StrEndsWithRector\Fixture;

class SkipLooseNumericString
{
    public function run($haystack)
    {
        // '+1' == '01' is true
        $isMatch = substr($haystack, -2) == '01';
    }
}

?>
