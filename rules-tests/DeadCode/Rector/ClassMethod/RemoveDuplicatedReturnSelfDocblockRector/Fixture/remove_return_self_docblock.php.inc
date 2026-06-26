<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveDuplicatedReturnSelfDocblockRector\Fixture;

final class RemoveReturnSelfDocblock
{
    /**
     * @return self
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

final class RemoveReturnSelfDocblock
{
    public function test(): self
    {
        return $this;
    }
}

?>
