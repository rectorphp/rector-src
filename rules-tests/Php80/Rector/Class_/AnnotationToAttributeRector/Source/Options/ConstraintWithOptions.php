<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Options;

use Attribute;

/**
 * Mimics Symfony validator constraints: only a few named constructor parameters, everything else goes via $options.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class ConstraintWithOptions
{
    /**
     * @param array<string, mixed> $options
     */
    public function __construct(
        public ?int $min = null,
        public ?int $max = null,
        public ?string $message = null,
        public array $options = [],
    ) {
    }
}
