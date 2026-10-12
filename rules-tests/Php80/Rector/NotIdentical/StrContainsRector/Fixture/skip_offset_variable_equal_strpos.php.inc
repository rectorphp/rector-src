<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class SkipOffsetVariableEqualStrpos
{
    public function run()
    {
        $offset = 1;
        $isMatch = strpos('abc', 'a', $offset) != false;
    }
}
?>
