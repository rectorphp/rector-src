<?php

declare(strict_types=1);

namespace Rector\Arguments\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Arguments\Contract\ReplaceArgumentDefaultValueInterface;
use Rector\Validation\RectorAssert;

final class ReplaceArgumentDefaultValue implements ReplaceArgumentDefaultValueInterface
{
    /**
     * @var string
     */
    public const ANY_VALUE_BEFORE = '*ANY_VALUE_BEFORE*';

    /**
     * @param int<0, max> $position
     */
    public function __construct(
        private readonly string $class,
        private readonly string $method,
        private readonly int $position,
        private readonly mixed $valueBefore,
        private readonly mixed $valueAfter
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
