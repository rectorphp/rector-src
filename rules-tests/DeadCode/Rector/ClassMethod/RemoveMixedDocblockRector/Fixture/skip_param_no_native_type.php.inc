<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveMixedDocblockRector\Fixture;

final class SkipParamNoNativeType
{
    /**
     * @param mixed $value
     */
    public function run($value): void
    {
    }
}
