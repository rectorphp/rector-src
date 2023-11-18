<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class OffsetZeroStrpos
{
    public function run()
    {
        $isMatch = strpos('abc', 'a', 0) !== false;
    }
}
?>
-----
<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class OffsetZeroStrpos
{
    public function run()
    {
        $isMatch = str_contains('abc', 'a');
    }
}
?>
