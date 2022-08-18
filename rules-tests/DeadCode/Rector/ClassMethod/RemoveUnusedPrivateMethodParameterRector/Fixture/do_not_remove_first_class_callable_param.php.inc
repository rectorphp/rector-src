<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

final class DoNotRemoveFirstClassCallableParam
{
    public function run()
    {
        $this->test(...);
    }

    private function test($foo, $bar)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

final class DoNotRemoveFirstClassCallableParam
{
    public function run()
    {
        $this->test(...);
    }

    private function test()
    {
    }
}

?>
