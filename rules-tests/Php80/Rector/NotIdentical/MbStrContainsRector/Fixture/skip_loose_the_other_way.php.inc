<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class SkipLooseTheOtherWay
{
    public function run()
    {
        $isMatch = false != mb_strpos('abc', 'a');
    }
}

?>
