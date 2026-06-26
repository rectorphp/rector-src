<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveDuplicatedReturnSelfDocblockRector\Fixture;

final class RemoveReturnThisDocblock
{
    /**
     * @return $this
     */
    public function test(): self
    {
        return $this;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveDuplicatedReturnSelfDocblockRector\Fixture;

final class RemoveReturnThisDocblock
{
    public function test(): self
    {
        return $this;
    }
}

?>
