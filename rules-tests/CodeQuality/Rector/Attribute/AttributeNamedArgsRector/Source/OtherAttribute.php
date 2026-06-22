<?php

namespace Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Source;

#[\Attribute]
class OtherAttribute
{
    public function __construct($a = null, $b = null)
    {
    }
}
