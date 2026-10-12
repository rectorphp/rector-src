<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class SkipOffsetVariableStrpos
{
    public function run()
    {
        $offset = 1;
        $isMatch = strpos('abc', 'a', $offset) !== false;
    }
}
?>
