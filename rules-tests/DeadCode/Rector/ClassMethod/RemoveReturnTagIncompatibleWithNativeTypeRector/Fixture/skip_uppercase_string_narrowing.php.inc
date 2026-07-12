<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

final class SkipUppercaseStringNarrowing
{
    /**
     * @return uppercase-string
     */
    public function run(): string
    {
        return 'X';
    }
}
