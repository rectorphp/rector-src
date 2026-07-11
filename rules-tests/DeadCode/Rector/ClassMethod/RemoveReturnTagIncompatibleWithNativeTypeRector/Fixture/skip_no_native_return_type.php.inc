<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

final class SkipNoNativeReturnType
{
    /**
     * @return \stdClass
     */
    public function getName()
    {
        return new \stdClass();
    }
}
