<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

final class SkipNonFalsyStringNarrowing
{
    /**
     * @return non-falsy-string
     */
    public function run(): string
    {
        return 'x';
    }
}
