<?php

declare(strict_types=1);

namespace Rector\Removing\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Core\Validation\RectorAssert;

final class ArgumentRemover
{
    /**
     * @param class-string $class
     */
    public function __construct(
        private readonly string $class,
        private readonly string $method,
        private readonly int $position,
        private readonly mixed $value
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

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
