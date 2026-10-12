<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class SkipOffsetStrpos
{
    public function run()
    {
        $isMatch = strpos('abc', 'a', 1) !== false;
    }
}
?>
