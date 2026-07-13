<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

/**
 * @template TPayload
 * @template TDomainId
 */
final class SkipGenericFluentBuilder
{
    /**
     * @return SkipGenericFluentBuilder<TPayload, TDomainId>
     */
    public function withFoo(): self
    {
        return $this;
    }

    /**
     * @return SkipGenericFluentBuilder<TPayload, TDomainId>
     */
    public function withBar(): self
    {
        return $this;
    }
}
