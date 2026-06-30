<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessUnionReturnDocblockRector\Fixture;

final class SkipNullableArrayNative
{
    /**
     * @return mixed[]|null
     */
    public function run(): ?array
    {
    }
}
