<?php

declare(strict_types=1);

namespace Rector\Tests\Console\Command\Source;

final class SomeConfigurationValueObject
{
    public function __construct(
        private readonly string $annotationName,
        private readonly string $attributeClass
    ) {
    }
}
