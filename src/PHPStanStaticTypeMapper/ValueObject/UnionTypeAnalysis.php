<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\ValueObject;

final readonly class UnionTypeAnalysis
{
    public function __construct(
        private bool $hasIterable,
        private bool $hasArray
    ) {
    }

    public function hasIterable(): bool
    {
        return $this->hasIterable;
    }

    public function hasArray(): bool
    {
        return $this->hasArray;
    }
}
