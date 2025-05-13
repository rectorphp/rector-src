<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class MultibyteStrposFunction
{
    public function run()
    {
        $isMatch = mb_strpos('abc', 'a') !== false;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class MultibyteStrposFunction
{
    public function run()
    {
        $isMatch = str_contains('abc', 'a');
    }
}

?>
