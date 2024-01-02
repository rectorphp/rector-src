<?php

declare(strict_types=1);

namespace Rector\Php80\ValueObject;

use Rector\Validation\RectorAssert;

final readonly class AnnotationPropertyToAttributeClass
{
    public function __construct(
        private string $attributeClass,
        private string|int|null $annotationProperty = null,
        private bool $doesNeedNewImport = false
    ) {
        RectorAssert::className($attributeClass);
    }

    public function getAnnotationProperty(): string|int|null
    {
        return $this->annotationProperty;
    }

    public function getAttributeClass(): string
    {
        return $this->attributeClass;
    }

    public function doesNeedNewImport(): bool
    {
        return $this->doesNeedNewImport;
    }
}
