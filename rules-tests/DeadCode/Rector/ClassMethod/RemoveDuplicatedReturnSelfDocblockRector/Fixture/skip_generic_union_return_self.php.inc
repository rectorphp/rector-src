<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveDuplicatedReturnSelfDocblockRector\Fixture;

/**
 * @template TFirst of scalar
 */
abstract readonly class SkipGenericUnionReturnSelf
{
    /**
     * @return static<string|int>
     */
    public function get(): self
    {
        return $this;
    }
}
