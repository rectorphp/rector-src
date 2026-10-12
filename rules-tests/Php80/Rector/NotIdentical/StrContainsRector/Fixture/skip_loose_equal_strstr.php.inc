<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class SkipLooseEqualStrstr
{
    public function run()
    {
        $isMatch = strstr('abc', 'a') == false;
    }
}

?>
