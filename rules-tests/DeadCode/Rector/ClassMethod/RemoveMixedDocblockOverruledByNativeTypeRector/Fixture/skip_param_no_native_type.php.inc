<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveMixedDocblockOverruledByNativeTypeRector\Fixture;

final class SkipParamNoNativeType
{
    /**
     * @param mixed $value
     */
    public function run($value): void
    {
    }
}
