<?php

namespace Rector\Tests\DeadCode\Rector\Ternary\RemoveUselessTernaryRector\Fixture;

class SkipDocblockType
{
    /**
     * @param bool $value
     */
    public function go($value)
    {
        return $value ?: false;
    }
}
