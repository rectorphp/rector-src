<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

final class RemoveThisOnSelfReturn
{
    /**
     * @return $this
     */
    function foo(): self
    {
        return $this;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

final class RemoveThisOnSelfReturn
{
    function foo(): self
    {
        return $this;
    }
}

?>
