<?php

namespace Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Fixture;

use Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromClassConstantAttribute;

#[RemoveFromClassConstantAttribute]
class RemoveAttributeFromClassOnly
{
    #[RemoveFromClassConstantAttribute]
    private array $property;
    #[RemoveFromClassConstantAttribute]
    private const CONSTANT = 1;
    #[RemoveFromClassConstantAttribute]
    public function __construct(
        #[RemoveFromClassConstantAttribute]
        public array $promotedProperty
    ) {}
    #[RemoveFromClassConstantAttribute]
    public function doSomething() {
    }
    #[RemoveFromClassConstantAttribute]
    public function doSomething2(
        #[RemoveFromClassConstantAttribute]
        string $parameter,
    ) {
    }
}

#[RemoveFromClassConstantAttribute]
function doSomething3()
{
}

?>
-----
<?php

namespace Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Fixture;

use Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromClassConstantAttribute;

#[RemoveFromClassConstantAttribute]
class RemoveAttributeFromClassOnly
{
    #[RemoveFromClassConstantAttribute]
    private array $property;
    private const CONSTANT = 1;
    #[RemoveFromClassConstantAttribute]
    public function __construct(
        #[RemoveFromClassConstantAttribute]
        public array $promotedProperty
    ) {}
    #[RemoveFromClassConstantAttribute]
    public function doSomething() {
    }
    #[RemoveFromClassConstantAttribute]
    public function doSomething2(
        #[RemoveFromClassConstantAttribute]
        string $parameter,
    ) {
    }
}

#[RemoveFromClassConstantAttribute]
function doSomething3()
{
}

?>
