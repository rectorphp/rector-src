<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

final class SkipNonNegativeIntNarrowing
{
    /**
     * @return non-negative-int
     */
    public function run(): int
    {
        return 0;
    }
}
