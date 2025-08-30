<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class TheOtherWay
{
    public function run()
    {
        $isMatch = false !== mb_strpos('abc', 'a');
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class TheOtherWay
{
    public function run()
    {
        $isMatch = str_contains('abc', 'a');
    }
}

?>
