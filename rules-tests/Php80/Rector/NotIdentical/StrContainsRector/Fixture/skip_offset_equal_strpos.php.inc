<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class SkipOffsetEqualStrpos
{
    public function run()
    {
        $isMatch = strpos('abc', 'a', 1) != false;
    }
}
?>
