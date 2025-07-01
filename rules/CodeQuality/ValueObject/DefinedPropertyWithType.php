<?php

declare(strict_types=1);

namespace Rector\CodeQuality\ValueObject;

final readonly class DefinedPropertyWithType
{
    public function __construct(
        private string $propertyName,
        private \PHPStan\Type\Type $type,
        private ?string $definedInMethodName
    ) {
    }

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    public function getType(): \PHPStan\Type\Type
    {
        return $this->type;
    }

    public function getDefinedInMethodName(): ?string
    {
        return $this->definedInMethodName;
    }
}
