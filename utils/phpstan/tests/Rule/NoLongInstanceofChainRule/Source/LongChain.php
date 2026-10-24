<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\NoLongInstanceofChainRule\Source;

final class LongChain
{
    public function run(object $value): bool
    {
        // 8 instanceof wrapped in a trailing && - nested chain must be reported once
        return (
            $value instanceof \DateTimeInterface ||
            $value instanceof \Countable ||
            $value instanceof \Traversable ||
            $value instanceof \Stringable ||
            $value instanceof \JsonSerializable ||
            $value instanceof \IteratorAggregate ||
            $value instanceof \ArrayAccess ||
            $value instanceof \Serializable
        ) && $value instanceof \Throwable;
    }
}
