<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Tests\Rule\NoLongInstanceofChainRule\Source;

final class SkipShortChain
{
    public function run(object $value): bool
    {
        // exactly 7 instanceof - at the allowed limit, not reported
        return $value instanceof \DateTimeInterface
            || $value instanceof \Countable
            || $value instanceof \Traversable
            || $value instanceof \Stringable
            || $value instanceof \JsonSerializable
            || $value instanceof \IteratorAggregate
            || $value instanceof \ArrayAccess;
    }
}
