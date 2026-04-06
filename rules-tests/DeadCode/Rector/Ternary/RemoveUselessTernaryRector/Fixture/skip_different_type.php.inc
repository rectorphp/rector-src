<?php

namespace Rector\Tests\DeadCode\Rector\Ternary\RemoveUselessTernaryRector\Fixture;

class SkipDifferentType
{
    public function go(bool $value)
    {
        return $value ?: 0;
    }
}
