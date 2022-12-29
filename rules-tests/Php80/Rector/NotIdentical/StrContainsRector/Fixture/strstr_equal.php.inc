<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class Strstr
{
    public function run()
    {
        $isMatch = strstr('abc', 'a') != false;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class Strstr
{
    public function run()
    {
        $isMatch = str_contains('abc', 'a');
    }
}

?>
