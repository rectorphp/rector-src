<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveDuplicatedReturnSelfDocblockRector\Fixture;

final class RemoveReturnCurrentObjectDocblock
{
    /**
     * @return RemoveReturnCurrentObjectDocblock
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

final class RemoveReturnCurrentObjectDocblock
{
    public function test(): self
    {
        return $this;
    }
}

?>
