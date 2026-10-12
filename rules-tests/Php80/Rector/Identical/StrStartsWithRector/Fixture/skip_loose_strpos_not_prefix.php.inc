<?php

namespace Rector\Tests\Php80\Rector\Identical\StrStartsWithRector\Fixture;

class SkipLooseStrposNotPrefix
{
    public function run()
    {
        $notMatch = strpos($haystack, $needle) != 0;
    }
}

?>
