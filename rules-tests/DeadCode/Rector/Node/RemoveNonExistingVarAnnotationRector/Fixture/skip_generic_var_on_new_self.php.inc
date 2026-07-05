<?php

namespace Rector\Tests\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector\Fixture;

/**
 * @template TSuccessValue
 * @template TFailureValue
 */
final class SkipGenericVarOnNewSelf
{
    public static function success(mixed $value = null): self
    {
        /** @var self<TSuccessValue, never> */
        return new self();
    }
}
