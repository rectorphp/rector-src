<?php

declare(strict_types=1);

namespace Rector\Arguments\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Arguments\Contract\ReplaceArgumentDefaultValueInterface;
use Rector\Validation\RectorAssert;

/**
 * @deprecated Use custom rule instead, see ReplaceArgumentDefaultValueRector deprecation.
 */
final readonly class ReplaceArgumentDefaultValue implements ReplaceArgumentDefaultValueInterface
{
    /**
     * @param int<0, max> $position
     */
    public function __construct(
        private string $class,
        private string $method,
        private int $position,
        private mixed $valueBefore,
        private mixed $valueAfter
    ) {
        RectorAssert::className($class);
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->class);
    }

    public function getMethod(): string
    {
        return $this->method;
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
