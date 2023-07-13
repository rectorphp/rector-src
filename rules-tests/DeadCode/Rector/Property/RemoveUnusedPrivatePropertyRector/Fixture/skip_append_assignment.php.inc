<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

class SkipAppendAssignment {
    private int $bar = 2;

    public function bar(): int {
        return $this->bar += 3;
    }
}
