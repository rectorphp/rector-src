<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use PHPStan\Type\ObjectType;

final class ArrayDimFetchToMethodCall
{
    public function __construct(
        private readonly ObjectType $objectType,
        private readonly string $method,
        // Optional methods for set, exists, and unset operations
        // if null, then these operations will not be transformed
        private readonly ?string $setMethod = null,
        private readonly ?string $existsMethod = null,
        private readonly ?string $unsetMethod = null,
    ) {
    }

    public function getObjectType(): ObjectType
    {
        return $this->objectType;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getSetMethod(): ?string
    {
        return $this->setMethod;
    }

    public function getExistsMethod(): ?string
    {
        return $this->existsMethod;
    }

    public function getUnsetMethod(): ?string
    {
        return $this->unsetMethod;
    }
}
