<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\MbStrContainsRector\Fixture;

class SkipOffsetStrpos
{
    public function run()
    {
        $isMatch = mb_strpos('abc', 'a', 1) !== false;
    }
}
?>
