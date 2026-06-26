<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveDuplicatedReturnSelfDocblockRector\Fixture;

class RemoveReturnStaticDocblock
{
    /**
     * @return static
     */
    public function test(): static
    {
        $obj = new static();
        return $obj;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveDuplicatedReturnSelfDocblockRector\Fixture;

class RemoveReturnStaticDocblock
{
    public function test(): static
    {
        $obj = new static();
        return $obj;
    }
}

?>
