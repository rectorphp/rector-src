<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class SkipLooseNotEqualStrpos
{
    public function run()
    {
        $isMatch = strpos('abc', 'a') != false;
    }
}

?>
