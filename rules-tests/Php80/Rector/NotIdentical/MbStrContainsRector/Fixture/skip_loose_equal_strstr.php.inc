<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\MbStrContainsRector\Fixture;

class SkipLooseEqualStrstr
{
    public function run()
    {
        $isMatch = mb_strstr('abc', 'a') == false;
    }
}

?>
