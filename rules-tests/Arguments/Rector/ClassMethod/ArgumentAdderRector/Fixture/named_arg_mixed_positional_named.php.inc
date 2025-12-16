<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source\SomeMultiArg;

class NamedArgMixedPositionalNamed
{
    public function run()
    {
        $obj = new SomeMultiArg();
        // Mix of positional and named arguments
        $obj->run(0, c: 6);
        $obj->run(0, b: 6);
    }
}

?>
-----
<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source\SomeMultiArg;

class NamedArgMixedPositionalNamed
{
    public function run()
    {
        $obj = new SomeMultiArg();
        // Mix of positional and named arguments
        $obj->run(0, c: 6);
        $obj->run(0, b: 6, c: 4);
    }
}

?>
