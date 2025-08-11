<?php

namespace Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Fixture;

use Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromPropertyAttribute;

#[RemoveFromPropertyAttribute]
class RemoveAttributeFromClassOnly
{
    #[RemoveFromPropertyAttribute]
    private array $property;
    #[RemoveFromPropertyAttribute]
    private const CONSTANT = 1;
    #[RemoveFromPropertyAttribute]
    public function __construct(
        #[RemoveFromPropertyAttribute]
        public array $promotedProperty
    ) {}
    #[RemoveFromPropertyAttribute]
    public function doSomething() {
    }
    #[RemoveFromPropertyAttribute]
    public function doSomething2(
        #[RemoveFromPropertyAttribute]
        string $parameter,
    ) {
    }
}

#[RemoveFromPropertyAttribute]
function doSomething3()
{
}

?>
-----
<?php

namespace Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Fixture;

use Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromPropertyAttribute;

#[RemoveFromPropertyAttribute]
class RemoveAttributeFromClassOnly
{
    private array $property;
    #[RemoveFromPropertyAttribute]
    private const CONSTANT = 1;
    #[RemoveFromPropertyAttribute]
    public function __construct(
        public array $promotedProperty
    ) {}
    #[RemoveFromPropertyAttribute]
    public function doSomething() {
    }
    #[RemoveFromPropertyAttribute]
    public function doSomething2(
        #[RemoveFromPropertyAttribute]
        string $parameter,
    ) {
    }
}

#[RemoveFromPropertyAttribute]
function doSomething3()
{
}

?>
