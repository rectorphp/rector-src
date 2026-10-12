<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class SkipLooseNotEqualStrstr
{
    public function run()
    {
        $isMatch = strstr('abc', 'a') != false;
    }
}

?>
