<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

class Foo {
    private int $bar;

    public function bar(): int {
        return $this->bar ??= 3;
    }
}
