<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\MbStrContainsRector\Fixture;

class Strstr
{
    public function run()
    {
        $isMatch = mb_strstr('abc', 'a') != false;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\MbStrContainsRector\Fixture;

class Strstr
{
    public function run()
    {
        $isMatch = str_contains('abc', 'a');
    }
}

?>
