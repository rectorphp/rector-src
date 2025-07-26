<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class MultibyteStrposFunction
{
    public function run()
    {
        $isMatch = mb_strpos('happy 😊 euro €', 'a') !== false;
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
        $isMatch = str_contains('happy 😊 euro €', 'a');
    }
}

?>
