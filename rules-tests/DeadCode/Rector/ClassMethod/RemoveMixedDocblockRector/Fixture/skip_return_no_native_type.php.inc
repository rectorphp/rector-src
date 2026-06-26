<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveMixedDocblockRector\Fixture;

final class SkipReturnNoNativeType
{
    /**
     * @return mixed
     */
    public function run()
    {
    }
}
