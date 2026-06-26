<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveMixedDocblockRector\Fixture;

final class SkipParamWithDescription
{
    /**
     * @param mixed $value the raw value
     */
    public function run(int $value): void
    {
    }
}
