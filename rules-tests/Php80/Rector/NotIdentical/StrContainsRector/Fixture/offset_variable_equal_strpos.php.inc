<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class OffsetVariableStrpos
{
    public function run()
    {
        $offset = 1;
        $isMatch = strpos('abc', 'a', $offset) != false;
    }
}
?>
-----
<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class OffsetVariableStrpos
{
    public function run()
    {
        $offset = 1;
        $isMatch = str_contains(substr('abc', $offset), 'a');
    }
}
?>
