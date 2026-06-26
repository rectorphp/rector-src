<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveDuplicatedReturnSelfDocblockRector\Fixture;

final class SkipOtherType
{
    /**
     * @return \stdClass
     */
    public function test(): self
    {
        return $this;
    }
}
