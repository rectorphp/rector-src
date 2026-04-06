<?php

namespace Rector\Tests\DeadCode\Rector\Ternary\RemoveUselessTernaryRector\Fixture;

class SkipNegatedCond
{
    public function go(bool $value)
    {
        return ! $value ?: false;
    }
}
