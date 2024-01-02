<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\ValueObject;

use PHPStan\Type\Type;
use Rector\Validation\RectorAssert;

final readonly class AddPropertyTypeDeclaration
{
    public function __construct(
        private string $class,
        private string $propertyName,
        private Type $type
    ) {
        RectorAssert::className($class);
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    public function getType(): Type
    {
        return $this->type;
    }
}
