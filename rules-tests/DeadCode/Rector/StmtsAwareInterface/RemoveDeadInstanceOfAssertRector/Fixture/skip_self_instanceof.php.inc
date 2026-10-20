<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveDeadInstanceOfAssertRector\Fixture;

final class SkipSelfInstanceof
{
    private self $item;

    public function run(): void
    {
        assert($this->item instanceof self);
    }
}
