<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class ReplaceMultibyteStrposWithEncodingNull
{
    public function run()
    {
        $isMatch = mb_strpos('happy 😊 euro €', 'a', 0, null) !== false;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class ReplaceMultibyteStrposWithEncodingNull
{
    public function run()
    {
        $isMatch = str_contains('happy 😊 euro €', 'a');
    }
}

?>
