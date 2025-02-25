<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\NewlineBeforeNewAssignSetRector\Fixture;

final class Objects
{
    private ?Value $foo = null;
    private ?Value $bar = null;
    public function run()
    {
        $this->foo = new Value;
        $this->foo->bar = 1;
        $this->bar = new Value;
        $this->bar->bar = 1;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\NewlineBeforeNewAssignSetRector\Fixture;

final class Objects
{
    private ?Value $foo = null;
    private ?Value $bar = null;
    public function run()
    {
        $this->foo = new Value;
        $this->foo->bar = 1;

        $this->bar = new Value;
        $this->bar->bar = 1;
    }
}

?>
