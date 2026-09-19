<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\CallLike\AddNameToNullArgumentRector\Source;

/**
 * @no-named-arguments
 */
final class NoNamedArgumentsService
{
    public function __construct(string $value, ?string $default)
    {
    }

    public function configure(string $value, ?string $default): void
    {
    }

    public static function create(string $value, ?string $default): self
    {
        return new self($value, $default);
    }
}
