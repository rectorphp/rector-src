<?php

namespace Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Fixture;

use Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromClassAttribute;

#[RemoveFromClassAttribute]
class RemoveAttributeFromClassOnly
{
    #[RemoveFromClassAttribute]
    private array $property;
    #[RemoveFromClassAttribute]
    private const CONSTANT = 1;
    #[RemoveFromClassAttribute]
    public function __construct(
        #[RemoveFromClassAttribute]
        public array $promotedProperty
    ) {}
    #[RemoveFromClassAttribute]
    public function doSomething() {
    }
    #[RemoveFromClassAttribute]
    public function doSomething2(
        #[RemoveFromClassAttribute]
        string $parameter,
    ) {
    }
}

#[RemoveFromClassAttribute]
function doSomething3()
{
}

?>
-----
<?php

namespace Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Fixture;

use Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromClassAttribute;

class RemoveAttributeFromClassOnly
{
    #[RemoveFromClassAttribute]
    private array $property;
    #[RemoveFromClassAttribute]
    private const CONSTANT = 1;
    #[RemoveFromClassAttribute]
    public function __construct(
        #[RemoveFromClassAttribute]
        public array $promotedProperty
    ) {}
    #[RemoveFromClassAttribute]
    public function doSomething() {
    }
    #[RemoveFromClassAttribute]
    public function doSomething2(
        #[RemoveFromClassAttribute]
        string $parameter,
    ) {
    }
}

#[RemoveFromClassAttribute]
function doSomething3()
{
}

?>
