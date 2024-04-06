<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source\SomeMultiArg;

class ByPass2andArgValue
{
    public function run()
    {
        $containerBuilder = new SomeMultiArg();
        $containerBuilder->run(1);
    }
}

?>
-----
<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source\SomeMultiArg;

class ByPass2andArgValue
{
    public function run()
    {
        $containerBuilder = new SomeMultiArg();
        $containerBuilder->run(1, 2, 4);
    }
}

?>
