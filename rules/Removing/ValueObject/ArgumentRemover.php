<?php

declare(strict_types=1);

namespace Rector\Removing\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Validation\RectorAssert;

final readonly class ArgumentRemover
{
    public function __construct(
        private string $class,
        private string $method,
        private int $position,
        private mixed $value
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

    public function getValue(): mixed
    {
        return $this->value;
    }
}
