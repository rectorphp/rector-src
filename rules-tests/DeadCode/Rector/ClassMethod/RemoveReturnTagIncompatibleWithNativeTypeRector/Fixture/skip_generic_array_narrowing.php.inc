<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

final class SkipGenericArrayNarrowing
{
    /**
     * @return int[]
     */
    public function getInts(): array
    {
        return [1, 2];
    }
}
