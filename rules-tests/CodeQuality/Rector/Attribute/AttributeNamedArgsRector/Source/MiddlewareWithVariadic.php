<?php

namespace Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Source;

#[\Attribute]
class MiddlewareWithVariadic
{
    public function __construct($middleware = null, ...$groups)
    {
    }
}
