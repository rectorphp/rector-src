<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

/**
 * @template TAdded
 * @template TRemoved
 */
final readonly class SkipGenericSelfReturn
{
    /**
     * @param list<TAdded> $added
     * @param list<TRemoved> $removed
     */
    private function __construct(
        public array $added,
        public array $removed,
    ) {
    }

    /**
     * @param list<TAdded> $added
     * @param list<TRemoved> $removed
     *
     * @return self<TAdded, TRemoved>
     */
    public static function create(array $added, array $removed): self
    {
        return new self($added, $removed);
    }
}
