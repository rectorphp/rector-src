<?php

declare(strict_types=1);

namespace Rector\Arguments\ValueObject;

use Rector\Arguments\Contract\ReplaceArgumentDefaultValueInterface;

final readonly class ReplaceFuncCallArgumentDefaultValue implements ReplaceArgumentDefaultValueInterface
{
    public function __construct(
        private string $function,
        private int $position,
        private mixed $valueBefore,
        private mixed $valueAfter
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
