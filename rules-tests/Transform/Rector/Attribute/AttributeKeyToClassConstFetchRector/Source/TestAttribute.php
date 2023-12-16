<?php

declare(strict_types=1);

namespace Rector\Tests\Transform\Rector\Attribute\AttributeKeyToClassConstFetchRector\Source;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class TestAttribute
{
    public function __construct(
        public readonly string $type,
    ) {
    }
}
