<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\MbStrContainsRector\Fixture;

class OffsetStrpos
{
    public function run()
    {
        $isMatch = mb_strpos('abc', 'a', 1) != false;
    }
}
?>
-----
<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\MbStrContainsRector\Fixture;

class OffsetStrpos
{
    public function run()
    {
        $isMatch = str_contains(mb_substr('abc', 1), 'a');
    }
}
?>
