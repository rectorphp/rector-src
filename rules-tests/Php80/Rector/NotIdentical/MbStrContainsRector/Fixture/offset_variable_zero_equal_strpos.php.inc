<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\MbStrContainsRector\Fixture;

class OffsetVariableZeroStrpos
{
    public function run()
    {
        $offset = 0;
        $isMatch = mb_strpos('abc', 'a', $offset) != false;
    }
}
?>
-----
<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\MbStrContainsRector\Fixture;

class OffsetVariableZeroStrpos
{
    public function run()
    {
        $offset = 0;
        $isMatch = str_contains(mb_substr('abc', $offset), 'a');
    }
}
?>
