<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

final class SkipLiteralStringNarrowing
{
    /**
     * @return literal-string
     */
    public function getTableAlias(): string
    {
        return 'n';
    }
}
