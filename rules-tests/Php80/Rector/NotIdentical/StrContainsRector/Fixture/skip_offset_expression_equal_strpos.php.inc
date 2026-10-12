<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class SkipOffsetExpressionEqualStrpos
{
    public function run()
    {
        $offset = 1;
        $isMatch = strpos('abc', 'a', $offset + 1) != false;
    }
}
?>
