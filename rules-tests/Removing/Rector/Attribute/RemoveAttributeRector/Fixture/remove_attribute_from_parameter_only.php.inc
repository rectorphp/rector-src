<?php

namespace Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Fixture;

use Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromParameterAttribute;

#[RemoveFromParameterAttribute]
class RemoveAttributeFromClassOnly
{
    #[RemoveFromParameterAttribute]
    private array $property;
    #[RemoveFromParameterAttribute]
    private const CONSTANT = 1;
    #[RemoveFromParameterAttribute]
    public function __construct(
        public array $promotedProperty
    ) {}
    #[RemoveFromParameterAttribute]
    public function doSomething() {
    }
    #[RemoveFromParameterAttribute]
    public function doSomething2(
        #[RemoveFromParameterAttribute]
        string $parameter,
    ) {
    }
}

#[RemoveFromParameterAttribute]
function doSomething3()
{
}

?>
-----
<?php

namespace Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Fixture;

use Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromParameterAttribute;

#[RemoveFromParameterAttribute]
class RemoveAttributeFromClassOnly
{
    #[RemoveFromParameterAttribute]
    private array $property;
    #[RemoveFromParameterAttribute]
    private const CONSTANT = 1;
    #[RemoveFromParameterAttribute]
    public function __construct(
        public array $promotedProperty
    ) {}
    #[RemoveFromParameterAttribute]
    public function doSomething() {
    }
    #[RemoveFromParameterAttribute]
    public function doSomething2(
        string $parameter,
    ) {
    }
}

#[RemoveFromParameterAttribute]
function doSomething3()
{
}

?>
