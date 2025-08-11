<?php

namespace Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Fixture;

use Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromTraitAttribute;

#[RemoveFromTraitAttribute]
trait RemoveAttributeFromTraitOnly
{
    #[RemoveFromTraitAttribute]
    private array $property;
    #[RemoveFromTraitAttribute]
    private const CONSTANT = 1;
    #[RemoveFromTraitAttribute]
    public function __construct(
        #[RemoveFromTraitAttribute]
        public array $promotedProperty
    ) {}
    #[RemoveFromTraitAttribute]
    public function doSomething() {
    }
    #[RemoveFromTraitAttribute]
    public function doSomething2(
        #[RemoveFromTraitAttribute]
        string $parameter,
    ) {
    }
}

#[RemoveFromTraitAttribute]
function doSomething3()
{
}

?>
-----
<?php

namespace Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Fixture;

use Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromTraitAttribute;

trait RemoveAttributeFromTraitOnly
{
    #[RemoveFromTraitAttribute]
    private array $property;
    #[RemoveFromTraitAttribute]
    private const CONSTANT = 1;
    #[RemoveFromTraitAttribute]
    public function __construct(
        #[RemoveFromTraitAttribute]
        public array $promotedProperty
    ) {}
    #[RemoveFromTraitAttribute]
    public function doSomething() {
    }
    #[RemoveFromTraitAttribute]
    public function doSomething2(
        #[RemoveFromTraitAttribute]
        string $parameter,
    ) {
    }
}

#[RemoveFromTraitAttribute]
function doSomething3()
{
}

?>
