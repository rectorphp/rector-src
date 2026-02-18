<?php

declare(strict_types=1);

namespace Behat\Transformation;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Transform
{
    public function __construct(
        public string $value
    ) {
    }
}
