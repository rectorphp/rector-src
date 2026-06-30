<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessUnionReturnDocblockRector\Fixture;

final class SkipUnionWithArrayNative
{
    /**
     * @return string|mixed[]|int
     */
    public function run(): string|array|int
    {
    }
}
