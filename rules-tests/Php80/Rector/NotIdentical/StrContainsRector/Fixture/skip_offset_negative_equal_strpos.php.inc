<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class SkipOffsetNegativeEqualStrpos
{
    public function run()
    {
        $isMatch = strpos('abc', 'a', -1) != false;
    }
}
?>
