<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

class SkipAppenderAssignment {
    private int $bar = 2;

    public function bar(): int {
        $a = 1;
        $a += $this->bar;

        return $a;
    }
}
