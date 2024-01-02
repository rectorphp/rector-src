<?php

declare(strict_types=1);

namespace Rector\Arguments\ValueObject;

use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\Validation\RectorAssert;

final readonly class ArgumentAdderWithoutDefaultValue
{
    public function __construct(
        private string $class,
        private string $method,
        private int $position,
        private ?string $argumentName = null,
        private Type | null $argumentType = null,
        private ?string $scope = null
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

    public function getArgumentName(): ?string
    {
        return $this->argumentName;
    }

    public function getArgumentType(): ?Type
    {
        return $this->argumentType;
    }

    public function getScope(): ?string
    {
        return $this->scope;
    }
}
