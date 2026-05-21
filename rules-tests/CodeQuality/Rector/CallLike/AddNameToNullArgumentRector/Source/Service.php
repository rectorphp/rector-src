<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\CallLike\AddNameToNullArgumentRector\Source;

final class Service
{
    public function __construct(string $value, ?string $default, ?string $fallback = null)
    {
    }

    public function configure(string $value, ?string $default, ?string $fallback = null): void
    {
    }

    public static function create(string $value, ?string $default, ?string $fallback = null): self
    {
        return new self($value, $default, $fallback);
    }
}
