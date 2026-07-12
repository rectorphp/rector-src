<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

final class SkipLowercaseStringNarrowing
{
    /**
     * @return lowercase-string
     */
    public function run(): string
    {
        return 'x';
    }
}
