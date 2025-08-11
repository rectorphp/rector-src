<?php

namespace Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Fixture;

use Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromEnumAttribute;

#[RemoveFromEnumAttribute]
enum RemoveAttributeFromEnumOnly
{
    #[RemoveFromEnumAttribute]
    case Foo;
}

?>
-----
<?php

namespace Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Fixture;

use Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromEnumAttribute;

enum RemoveAttributeFromEnumOnly
{
    #[RemoveFromEnumAttribute]
    case Foo;
}

?>
