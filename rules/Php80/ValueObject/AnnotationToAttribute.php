<?php

declare(strict_types=1);

namespace Rector\Php80\ValueObject;

use Rector\Php80\Contract\ValueObject\AnnotationToAttributeInterface;
use Rector\Validation\RectorAssert;
use Webmozart\Assert\Assert;

final readonly class AnnotationToAttribute implements AnnotationToAttributeInterface
{
    /**
     * @param string[] $classReferenceFields
     */
    public function __construct(
        private string $tag,
        private ?string $attributeClass = null,
        private array $classReferenceFields = [],
        private bool $useValueAsAttributeArgument = false,
    ) {
        RectorAssert::className($tag);

        if (is_string($attributeClass)) {
            RectorAssert::className($attributeClass);
        }

        Assert::allString($classReferenceFields);
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function getAttributeClass(): string
    {
        if ($this->attributeClass === null) {
            return $this->tag;
        }

        return $this->attributeClass;
    }

    /**
     * @return string[]
     */
    public function getClassReferenceFields(): array
    {
        return $this->classReferenceFields;
    }

    public function getUseValueAsAttributeArgument(): bool
    {
        return $this->useValueAsAttributeArgument;
    }
}
