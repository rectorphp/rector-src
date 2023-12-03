<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class OffsetExpressionStrpos
{
    public function run()
    {
        $offset = 1;
        $isMatch = strpos('abc', 'a', $offset + 1) !== false;
    }
}
?>
-----
<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class OffsetExpressionStrpos
{
    public function run()
    {
        $offset = 1;
        $isMatch = str_contains(substr('abc', $offset + 1), 'a');
    }
}
?>
