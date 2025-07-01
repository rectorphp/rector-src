<?php

declare(strict_types=1);

namespace Rector\CodeQuality\ValueObject;

use PHPStan\Type\Type;

final readonly class DefinedPropertyWithType
{
    public function __construct(
        private string $propertyName,
        private Type $type,
        private ?string $definedInMethodName
    ) {
    }

    public function getName(): string
    {
        return $this->propertyName;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function getDefinedInMethodName(): ?string
    {
        return $this->definedInMethodName;
    }
}
