<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveMixedDocblockOverruledByNativeTypeRector\Fixture;

final class SkipReturnNoNativeType
{
    /**
     * @return mixed
     */
    public function run()
    {
    }
}
