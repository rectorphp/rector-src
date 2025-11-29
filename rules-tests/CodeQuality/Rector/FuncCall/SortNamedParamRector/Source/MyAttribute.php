<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SortNamedParamRector\Source;

#[\Attribute]
class MyAttribute
{
    public function __construct($foo = null, $bar = null, $baz = null)
    {
    }
}
