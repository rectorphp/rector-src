<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\MbStrContainsRector\Fixture;

class OffsetZeroStrpos
{
    public function run()
    {
        $isMatch = mb_strpos('abc', 'a', 0) != false;
    }
}
?>
-----
<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\MbStrContainsRector\Fixture;

class OffsetZeroStrpos
{
    public function run()
    {
        $isMatch = str_contains('abc', 'a');
    }
}
?>
