<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use PHPStan\Type\ObjectType;

class ArrayDimFetchToMethodCall
{
    public function __construct(
        private readonly ObjectType $objectType,
        private readonly string $method
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
}
