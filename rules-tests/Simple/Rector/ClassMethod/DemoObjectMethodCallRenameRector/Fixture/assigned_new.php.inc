<?php

namespace Rector\Tests\Simple\Rector\ClassMethod\DemoObjectMethodCallRenameRector\Fixture;

class AssignedNew
{
    public function run()
    {
        $dateTime = new \DateTime();
        $dateTime->oldMethod();
    }
}

?>
-----
<?php

namespace Rector\Tests\Simple\Rector\ClassMethod\DemoObjectMethodCallRenameRector\Fixture;

class AssignedNew
{
    public function run()
    {
        $dateTime = new \DateTime();
        $dateTime->newMethod();
    }
}

?>
