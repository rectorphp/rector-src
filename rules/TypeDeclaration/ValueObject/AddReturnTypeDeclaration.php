<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\ValueObject;

use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\Validation\RectorAssert;

/**
 * @api
 */
final readonly class AddReturnTypeDeclaration
{
    public function __construct(
        private string $class,
        private string $method,
        private Type $returnType
    ) {
        RectorAssert::className($class);
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
        return $this->returnType;
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->class);
    }
}
