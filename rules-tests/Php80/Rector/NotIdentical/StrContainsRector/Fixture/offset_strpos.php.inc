<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class OffsetStrpos
{
    public function run()
    {
        $isMatch = strpos('abc', 'a', 1) !== false;
    }
}
?>
-----
<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class OffsetStrpos
{
    public function run()
    {
        $isMatch = str_contains(substr('abc', 1), 'a');
    }
}
?>
