<?php

namespace Rector\Tests\DeadCode\Rector\Ternary\RemoveUselessTernaryRector\Fixture;

class SkipWithIfNotEqual
{
    public function go(bool $value)
    {
        return $value ? 1 : false;
    }
}
