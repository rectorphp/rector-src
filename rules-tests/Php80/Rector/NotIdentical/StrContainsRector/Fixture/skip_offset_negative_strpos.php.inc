<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class SkipOffsetNegativeStrpos
{
    public function run()
    {
        $isMatch = strpos('abc', 'a', -1) !== false;
    }
}
?>
