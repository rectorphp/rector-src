<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

final class SkipNonPositiveIntNarrowing
{
    /**
     * @return non-positive-int
     */
    public function run(): int
    {
        return 0;
    }
}
