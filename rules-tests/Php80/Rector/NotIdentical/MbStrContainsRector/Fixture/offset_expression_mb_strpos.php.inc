<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\MbStrContainsRector\Fixture;

class OffsetExpressionStrpos
{
    public function run()
    {
        $offset = 1;
        $isMatch = mb_strpos('abc', 'a', $offset + 1) !== false;
    }
}
?>
-----
<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\MbStrContainsRector\Fixture;

class OffsetExpressionStrpos
{
    public function run()
    {
        $offset = 1;
        $isMatch = str_contains(mb_substr('abc', $offset + 1), 'a');
    }
}
?>
