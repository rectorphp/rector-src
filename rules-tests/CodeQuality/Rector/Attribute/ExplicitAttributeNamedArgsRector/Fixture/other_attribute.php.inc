<?php

namespace Rector\Tests\CodeQuality\Rector\Attribute\ExplicitAttributeNamedArgsRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Attribute\ExplicitAttributeNamedArgsRector\Source\OtherAttribute;

#[OtherAttribute('a', 'b')]
class OtherAttributeFixture
{
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Attribute\ExplicitAttributeNamedArgsRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Attribute\ExplicitAttributeNamedArgsRector\Source\OtherAttribute;

#[OtherAttribute(a: 'a', b: 'b')]
class OtherAttributeFixture
{
}

?>
