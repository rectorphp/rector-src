<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\MbStrContainsRector\Fixture;

class SkipOffsetVariableEqualStrpos
{
    public function run()
    {
        $offset = 1;
        $isMatch = mb_strpos('abc', 'a', $offset) != false;
    }
}
?>
