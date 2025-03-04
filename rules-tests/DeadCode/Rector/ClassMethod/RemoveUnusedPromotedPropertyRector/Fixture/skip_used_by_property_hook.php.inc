<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector\Fixture;

final class SkipUsedByPropertyHook
{
    private int $sum {
        get => $this->a + $this->b;
    }

    public function __construct(
       	private readonly int $a,
       	private readonly int $b,
    ) {}

    public function getSum(): int
    {
        return $this->sum;
    }
}
