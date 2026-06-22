<?php

namespace Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Source;

#[\Attribute]
class Middleware
{
    public function __construct($middleware = null, $only = null, $except = null)
    {
    }
}
