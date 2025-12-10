<?php

namespace Rector\Tests\CodeQuality\Rector\Attribute\SortAttributeNamedArgsRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Attribute\SortAttributeNamedArgsRector\Source\MyAttribute;

#[MyAttribute(bar: 1, foo: 2)]
#[MyAttribute(1, baz: 2, bar: 3)]
final class HandleAttribute
{
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Attribute\SortAttributeNamedArgsRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Attribute\SortAttributeNamedArgsRector\Source\MyAttribute;

#[MyAttribute(foo: 2, bar: 1)]
#[MyAttribute(1, bar: 3, baz: 2)]
final class HandleAttribute
{
}

?>
