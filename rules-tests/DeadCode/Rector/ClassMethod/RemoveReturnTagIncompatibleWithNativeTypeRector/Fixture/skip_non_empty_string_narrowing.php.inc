<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

final class SkipNonEmptyStringNarrowing
{
    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return 'name';
    }
}
