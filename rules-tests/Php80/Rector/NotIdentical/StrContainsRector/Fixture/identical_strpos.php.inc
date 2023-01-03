<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class IdenticalStrpos
{
    public function run()
    {
        $isMatch = strpos('abc', 'a') === false;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class IdenticalStrpos
{
    public function run()
    {
        $isMatch = !str_contains('abc', 'a');
    }
}

?>
