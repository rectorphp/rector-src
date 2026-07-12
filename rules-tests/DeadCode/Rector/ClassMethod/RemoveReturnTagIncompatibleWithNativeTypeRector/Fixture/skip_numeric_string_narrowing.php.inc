<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

final class SkipNumericStringNarrowing
{
    /**
     * @return numeric-string
     */
    public function run(): string
    {
        return '1';
    }
}
