<?php

namespace Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Fixture;

use Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveEverywhereAttribute;

#[RemoveEverywhereAttribute]
class RemoveEverywhereAttribute
{
    #[RemoveEverywhereAttribute]
    private array $property;
    #[RemoveEverywhereAttribute]
    private const CONSTANT = 1;
    #[RemoveEverywhereAttribute]
    public function __construct(
        #[RemoveEverywhereAttribute]
        public array $promotedProperty
    ) {
    }
    #[RemoveEverywhereAttribute]
    public function doSomething()
    {
    }
    #[RemoveEverywhereAttribute]
    public function doSomething2(
        #[RemoveEverywhereAttribute]
        string $parameter,
    ) {
    }
}

#[RemoveEverywhereAttribute]
function doSomething3()
{
}

?>
-----
<?php

namespace Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Fixture;

use Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveEverywhereAttribute;

class RemoveEverywhereAttribute
{
    private array $property;
    private const CONSTANT = 1;
    public function __construct(public array $promotedProperty)
    {
    }
    public function doSomething()
    {
    }
    public function doSomething2(string $parameter)
    {
    }
}

function doSomething3()
{
}

?>
