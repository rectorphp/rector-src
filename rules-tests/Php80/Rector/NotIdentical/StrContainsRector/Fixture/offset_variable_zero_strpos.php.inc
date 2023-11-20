<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class OffsetVariableZeroStrpos
{
    public function run()
    {
        $offset = 0;
        $isMatch = strpos('abc', 'a', $offset) !== false;
    }
}
?>
-----
<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class OffsetVariableZeroStrpos
{
    public function run()
    {
        $offset = 0;
        $isMatch = str_contains(substr('abc', $offset), 'a');
    }
}
?>
