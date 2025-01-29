<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\NewlineBeforeNewAssignSetRector\Fixture;

final class Properties
{
    public function run()
    {
        $foo = new Value;
        $foo->bar = new Value;
        $foo->bar->bar = 5;
        $bar = new Value;
        $bar->foo = 1;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\NewlineBeforeNewAssignSetRector\Fixture;

final class Properties
{
    public function run()
    {
        $foo = new Value;
        $foo->bar = new Value;
        $foo->bar->bar = 5;

        $bar = new Value;
        $bar->foo = 1;
    }
}

?>
