<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\CountArrayToEmptyArrayComparisonRector\Fixture;

final class SkipCountableInstance implements \Countable
{
    public function isNotEmpty(): bool
    {
        return 0 < \count($this);
    }

    public function count(): int
    {
        return 0;
    }
}
