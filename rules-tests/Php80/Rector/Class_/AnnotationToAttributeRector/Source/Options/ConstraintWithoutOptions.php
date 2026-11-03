<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Options;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class ConstraintWithoutOptions
{
    public function __construct(
        public ?int $min = null,
    ) {
    }
}
