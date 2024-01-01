<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\ValueObject;

final class UnionTypeAnalysis
{
    public function __construct(
        private readonly bool $hasIterable,
        private readonly bool $hasArray
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
