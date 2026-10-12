<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\MbStrContainsRector\Fixture;

class SkipLooseNotEqualStrpos
{
    public function run()
    {
        $isMatch = mb_strpos('😊abc', 'a') != false;
    }
}

?>
