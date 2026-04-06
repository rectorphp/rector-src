<?php

namespace Rector\Tests\DeadCode\Rector\Ternary\RemoveUselessTernaryRector\Fixture;

class SkipUnion
{
    public function go(bool|string $value)
    {
        return $value ?: false;
    }
}
