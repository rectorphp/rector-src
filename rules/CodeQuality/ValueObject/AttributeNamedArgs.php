<?php

declare(strict_types=1);

namespace Rector\CodeQuality\ValueObject;

final readonly class AttributeNamedArgs
{
    /**
     * @param class-string $attributeClass     the attribute whose positional arguments should be named
     * @param int          $firstNamedPosition first positional index to name (0 = name all arguments)
     */
    public function __construct(
        private string $attributeClass,
        private int $firstNamedPosition = 0
    ) {
    }

    /**
     * @return class-string
     */
    public function getAttributeClass(): string
    {
        return $this->attributeClass;
    }

    public function getFirstNamedPosition(): int
    {
        return $this->firstNamedPosition;
    }
}
