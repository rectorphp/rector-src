<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class MultibyteOffsetStrpos
{
    public function run()
    {
        $isMatch = mb_strpos('happy 😊 euro €', 'e', 8) !== false;
    }
}
?>
-----
<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class MultibyteOffsetStrpos
{
    public function run()
    {
        $isMatch = str_contains(mb_substr('happy 😊 euro €', 8), 'e');
    }
}
?>
