<?php

namespace Rector\Tests\CodeQuality\Rector\Attribute\ExplicitAttributeNamedArgsRector\Source;

#[\Attribute]
class MiddlewareWithVariadic
{
    public function __construct($middleware = null, ...$groups)
    {
    }
}
