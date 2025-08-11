<?php

namespace Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Fixture;

use Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromInterfaceAttribute;

#[RemoveFromInterfaceAttribute]
interface RemoveAttributeFromInterfaceOnly
{
    #[RemoveFromInterfaceAttribute]
    const CONSTANT = 1;
    #[RemoveFromInterfaceAttribute]
    public function doSomething2(
        #[RemoveFromInterfaceAttribute]
        string $parameter,
    ) {
    }
}

#[RemoveFromInterfaceAttribute]
function doSomething3()
{
}

?>
-----
<?php

namespace Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Fixture;

use Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromInterfaceAttribute;

interface RemoveAttributeFromInterfaceOnly
{
    #[RemoveFromInterfaceAttribute]
    const CONSTANT = 1;
    #[RemoveFromInterfaceAttribute]
    public function doSomething2(
        #[RemoveFromInterfaceAttribute]
        string $parameter,
    ) {
    }
}

#[RemoveFromInterfaceAttribute]
function doSomething3()
{
}

?>
