<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\CallLike\NameBooleanOrNullArgumentRector\Source;

final class Service
{
    public function __construct(string $value, bool $strict, ?string $fallback)
    {
    }

    public function configure(string $value, bool $strict, ?string $fallback): void
    {
    }

    public static function create(string $value, bool $strict, ?string $fallback): self
    {
        return new self($value, $strict, $fallback);
    }
}
