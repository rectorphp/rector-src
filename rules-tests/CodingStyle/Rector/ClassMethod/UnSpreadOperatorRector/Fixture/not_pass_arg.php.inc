<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\UnSpreadOperatorRector\Fixture;

final class NotPassArg
{
    public function run(...$args)
    {
    }

    public function execute(array $data)
    {
        $this->run();
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\UnSpreadOperatorRector\Fixture;

final class NotPassArg
{
    public function run(array $args = [])
    {
    }

    public function execute(array $data)
    {
        $this->run();
    }
}

?>
