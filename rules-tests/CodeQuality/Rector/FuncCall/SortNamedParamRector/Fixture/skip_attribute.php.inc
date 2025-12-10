<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SortNamedParamRector\Fixture;

use Rector\Tests\CodeQuality\Rector\FuncCall\SortNamedParamRector\Source\MyAttribute;

#[MyAttribute(bar: 1, foo: 2)]
#[MyAttribute(1, baz: 2, bar: 3)]
class SkipAttribute
{
}
