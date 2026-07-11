<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

final class SkipReturnWithDescription
{
    /**
     * @return \stdClass the wrapped value
     */
    public function getName(): string
    {
        return 'name';
    }
}
