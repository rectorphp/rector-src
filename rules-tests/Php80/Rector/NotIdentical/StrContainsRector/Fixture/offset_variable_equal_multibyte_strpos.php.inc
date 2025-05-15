<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class OffsetVariableMultibyteStrpos
{
    public function run()
    {
        $offset = 1;
        $isMatch = mb_strpos('happy 😊 euro €', 'a', $offset) != false;
    }
}
?>
-----
<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class OffsetVariableMultibyteStrpos
{
    public function run()
    {
        $offset = 1;
        $isMatch = str_contains(mb_substr('happy 😊 euro €', $offset), 'a');
    }
}
?>
