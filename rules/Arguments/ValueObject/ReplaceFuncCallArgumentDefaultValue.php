<?php

declare(strict_types=1);

namespace Rector\Arguments\ValueObject;

use Rector\Arguments\Contract\ReplaceArgumentDefaultValueInterface;

final class ReplaceFuncCallArgumentDefaultValue implements ReplaceArgumentDefaultValueInterface
{
    public function __construct(
        private readonly string $function,
        private readonly int $position,
        private readonly mixed $valueBefore,
        private readonly mixed $valueAfter
    ) {
    }

    public function getFunction(): string
    {
        return $this->function;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getValueBefore(): mixed
    {
        return $this->valueBefore;
    }

    public function getValueAfter(): mixed
    {
        return $this->valueAfter;
    }
}
