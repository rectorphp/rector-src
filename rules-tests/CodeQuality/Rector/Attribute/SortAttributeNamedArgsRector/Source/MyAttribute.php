<?php

namespace Rector\Tests\CodeQuality\Rector\Attribute\SortAttributeNamedArgsRector\Source;

#[\Attribute]
class MyAttribute
{
    public function __construct($foo = null, $bar = null, $baz = null)
    {
    }
}
