<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveDuplicatedReturnSelfDocblockRector\Fixture;

/**
 * @template TSuccessValue
 * @template TFailureValue
 */
final class SkipGenericReturnSelf
{
    /**
     * @return self<TSuccessValue, never>
     */
    public function success(): self
    {
        return $this;
    }
}
