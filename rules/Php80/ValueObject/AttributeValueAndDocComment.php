<?php

declare(strict_types=1);

namespace Rector\Php80\ValueObject;

final readonly class AttributeValueAndDocComment
{
    public function __construct(
        public string $attributeValue,
        public string $docComment
    ) {
    }
}
