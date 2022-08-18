<?php

declare(strict_types=1);

namespace Rector\Php80\ValueObject;

use Rector\Core\Validation\RectorAssert;
use Rector\Php80\Contract\ValueObject\AnnotationToAttributeInterface;

final class NestedAnnotationToAttribute implements AnnotationToAttributeInterface
{
    /**
     * @param array<string, string>|string[] $annotationPropertiesToAttributeClasses
     */
    public function __construct(
        private readonly string $tag,
        private readonly array $annotationPropertiesToAttributeClasses,
        private bool $removeOriginal = false
    ) {
        RectorAssert::className($tag);
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @return array<string, string>|string[]
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
}
