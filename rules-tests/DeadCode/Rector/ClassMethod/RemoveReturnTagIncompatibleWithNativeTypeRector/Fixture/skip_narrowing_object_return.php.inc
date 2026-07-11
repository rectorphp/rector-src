<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

final class SkipNarrowingObjectReturn
{
    /**
     * @return \stdClass
     */
    public function create(): object
    {
        return new \stdClass();
    }
}
