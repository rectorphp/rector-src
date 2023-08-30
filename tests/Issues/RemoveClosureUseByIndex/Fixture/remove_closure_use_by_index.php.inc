<?php

namespace Rector\Core\Tests\Issues\DoubleRun\Fixture;

final class RemoveClosureUseByIndex
{
    public function run()
    {
        function () use ($a, $b, $c) {
        };
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\DoubleRun\Fixture;

final class RemoveClosureUseByIndex
{
    public function run()
    {
        function () use ($a, $d) {
        };
    }
}

?>
