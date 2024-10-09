<?php

namespace Rector\Tests\CodeQuality\Rector\If_\SimplifyIfNotNullReturnRector\Fixture;

final class SkipWithElseIf
{
    private ?int $foo = null;
    private ?int $bar = null;

    public function isTrue($value)
    {
        if ($this->foo !== null) {
            return $this->foo;
        } elseif ((bool) rand(0, 1)) {
            return $this->bar;
        }

        return null;
    }
}
