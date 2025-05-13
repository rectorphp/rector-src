<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class MultibyteStrstrFunction
{
    public function run()
    {
        $isMatch = mb_strstr('abc', 'a') === false;
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
        $isMatch = !str_contains('abc', 'a');
    }
}

?>
