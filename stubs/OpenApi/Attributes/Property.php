<?php

declare(strict_types=1);

namespace OpenApi\Attributes;

if (class_exists('OpenApi\Attributes\Property')) {
    return;
}

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER | \Attribute::TARGET_CLASS_CONSTANT | \Attribute::IS_REPEATABLE)]
class Property
{
    public function __construct(mixed $example)
    {
    }
}
