<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessUnionReturnDocblockRector\Fixture;

final class SkipNoNativeType
{
    /**
     * @return bool|mixed|string
     */
    public function run()
    {
    }
}
