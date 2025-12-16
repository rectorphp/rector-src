<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source\SomeMultiArg;

class NamedArgAddNew
{
    public function run()
    {
        $obj = new SomeMultiArg();
        // Named arguments exist, but not the one we're adding (c)
        $obj->run(b: 5);
    }
}

?>
-----
<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source\SomeMultiArg;

class NamedArgAddNew
{
    public function run()
    {
        $obj = new SomeMultiArg();
        // Named arguments exist, but not the one we're adding (c)
        $obj->run(b: 5, c: 4);
    }
}

?>
