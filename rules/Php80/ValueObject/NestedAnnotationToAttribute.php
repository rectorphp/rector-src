<?php

declare(strict_types=1);

namespace Rector\Php80\ValueObject;

use Rector\Core\Validation\RectorAssert;
use Rector\Php80\Contract\ValueObject\AnnotationToAttributeInterface;

final class NestedAnnotationToAttribute implements AnnotationToAttributeInterface
{
    /**
     * @var AnnotationPropertyToAttributeClass[]
     */
    private array $annotationPropertiesToAttributeClasses = [];

    /**
     * @param array<string, string>|string[]|AnnotationPropertyToAttributeClass[] $annotationPropertiesToAttributeClasses
     */
    public function __construct(
        private readonly string $tag,
        array $annotationPropertiesToAttributeClasses,
        private readonly bool $removeOriginal = false,
    ) {
        RectorAssert::className($tag);

        // back compatibility for raw scalar values
        foreach ($annotationPropertiesToAttributeClasses as $annotationProperty => $attributeClass) {
            if ($attributeClass instanceof AnnotationPropertyToAttributeClass) {
                $this->annotationPropertiesToAttributeClasses[] = $attributeClass;
            } else {
                $this->annotationPropertiesToAttributeClasses[] = new AnnotationPropertyToAttributeClass(
                    $attributeClass,
                    $annotationProperty,
                );
            }
        }
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @return AnnotationPropertyToAttributeClass[]
     */
    public function getAnnotationPropertiesToAttributeClasses(): array
    {
        return $this->annotationPropertiesToAttributeClasses;
    }

    public function getAttributeClass(): string
    {
        return $this->tag;
    }

    public function shouldRemoveOriginal(): bool
    {
        return $this->removeOriginal;
    }

    public function hasExplicitParameters(): bool
    {
        foreach ($this->annotationPropertiesToAttributeClasses as $annotationPropertyToAttributeClass) {
            if (is_string($annotationPropertyToAttributeClass->getAnnotationProperty())) {
                return true;
            }
        }

        return false;
    }
}
