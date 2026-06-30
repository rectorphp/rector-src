<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessUnionReturnDocblockRector\Fixture;

final class SkipArrayNative
{
    /**
     * @return int[]|string[]
     */
    public function run(): array
    {
    }
}
