<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SortNamedParamRector\Fixture;

#[\Attribute]
class MyAttribute
{
    public function __construct($foo = null, $bar = null, $baz = null)
    {
    }
}

#[MyAttribute(bar: 1, foo: 2)]
#[MyAttribute(1, baz: 2, bar: 3)]
class Test
{
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SortNamedParamRector\Fixture;

#[\Attribute]
class MyAttribute
{
    public function __construct($foo = null, $bar = null, $baz = null)
    {
    }
}

#[MyAttribute(foo: 2, bar: 1)]
#[MyAttribute(1, bar: 3, baz: 2)]
class Test
{
}

?>
