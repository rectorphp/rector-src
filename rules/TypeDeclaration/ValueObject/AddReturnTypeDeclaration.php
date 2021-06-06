<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\ValueObject;

use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

final class AddReturnTypeDeclaration
{
    public function __construct(
        private string $class,
        private string $method,
        private Type $type
    ) {
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getReturnType(): Type
    {
        return $this->type;
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->class);
    }
}
