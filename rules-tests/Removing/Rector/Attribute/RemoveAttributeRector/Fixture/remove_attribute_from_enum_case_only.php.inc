<?php

namespace Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Fixture;

use Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromEnumCaseAttribute;

#[RemoveFromEnumCaseAttribute]
enum RemoveAttributeFromEnumOnly
{
    #[RemoveFromEnumCaseAttribute]
    case Foo;
}

?>
-----
<?php

namespace Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Fixture;

use Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromEnumCaseAttribute;

#[RemoveFromEnumCaseAttribute]
enum RemoveAttributeFromEnumOnly
{
    case Foo;
}

?>
