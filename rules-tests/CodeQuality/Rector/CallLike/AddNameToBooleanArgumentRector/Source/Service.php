<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\CallLike\AddNameToBooleanArgumentRector\Source;

final class Service
{
    public function __construct(string $value, bool $strict, ?string $fallback = null)
    {
    }

    public function configure(string $value, bool $strict, ?string $fallback = null): void
    {
    }

    public static function create(string $value, bool $strict, ?string $fallback = null): self
    {
        return new self($value, $strict, $fallback);
    }
}
