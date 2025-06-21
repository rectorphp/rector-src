<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class MultibyteStrstrFunction
{
    public function run()
    {
        $isMatch = mb_strstr('happy 😊 euro €', 'a') === false;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class MultibyteStrstrFunction
{
    public function run()
    {
        $isMatch = !str_contains('happy 😊 euro €', 'a');
    }
}

?>
