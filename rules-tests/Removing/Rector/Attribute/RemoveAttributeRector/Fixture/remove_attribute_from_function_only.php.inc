<?php

namespace Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Fixture;

use Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromFunctionAttribute;

#[RemoveFromFunctionAttribute]
class RemoveAttributeFromClassOnly
{
    #[RemoveFromFunctionAttribute]
    private array $property;
    #[RemoveFromFunctionAttribute]
    private const CONSTANT = 1;
    #[RemoveFromFunctionAttribute]
    public function __construct(
        #[RemoveFromFunctionAttribute]
        public array $promotedProperty
    ) {}
    #[RemoveFromFunctionAttribute]
    public function doSomething() {
    }
    #[RemoveFromFunctionAttribute]
    public function doSomething2(
        #[RemoveFromFunctionAttribute]
        string $parameter,
    ) {
    }
}

#[RemoveFromFunctionAttribute]
function doSomething3()
{
}

?>
-----
<?php

namespace Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Fixture;

use Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromFunctionAttribute;

#[RemoveFromFunctionAttribute]
class RemoveAttributeFromClassOnly
{
    #[RemoveFromFunctionAttribute]
    private array $property;
    #[RemoveFromFunctionAttribute]
    private const CONSTANT = 1;
    #[RemoveFromFunctionAttribute]
    public function __construct(
        #[RemoveFromFunctionAttribute]
        public array $promotedProperty
    ) {}
    #[RemoveFromFunctionAttribute]
    public function doSomething() {
    }
    #[RemoveFromFunctionAttribute]
    public function doSomething2(
        #[RemoveFromFunctionAttribute]
        string $parameter,
    ) {
    }
}

function doSomething3()
{
}

?>
