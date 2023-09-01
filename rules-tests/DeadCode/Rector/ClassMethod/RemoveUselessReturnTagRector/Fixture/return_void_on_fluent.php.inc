<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

final class ReturnVoidOnFluent
{
    /**
     * @return void
     */
    public function resolve(): self
    {
        return $this;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

final class ReturnVoidOnFluent
{
    public function resolve(): self
    {
        return $this;
    }
}

?>
