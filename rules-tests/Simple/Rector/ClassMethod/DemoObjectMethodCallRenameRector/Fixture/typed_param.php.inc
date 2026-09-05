<?php

namespace Rector\Tests\Simple\Rector\ClassMethod\DemoObjectMethodCallRenameRector\Fixture;

class TypedParam
{
    public function run(\DateTime $dateTime)
    {
        $dateTime->oldMethod();
    }
}

?>
-----
<?php

namespace Rector\Tests\Simple\Rector\ClassMethod\DemoObjectMethodCallRenameRector\Fixture;

class TypedParam
{
    public function run(\DateTime $dateTime)
    {
        $dateTime->newMethod();
    }
}

?>
