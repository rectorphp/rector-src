<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\CallLike\AddNameToBooleanArgumentRector\Source;

/**
 * @no-named-arguments
 */
final class NoNamedArgumentsService
{
    public function __construct(string $value, bool $strict)
    {
    }

    public function configure(string $value, bool $strict): void
    {
    }

    public static function create(string $value, bool $strict): self
    {
        return new self($value, $strict);
    }
}
