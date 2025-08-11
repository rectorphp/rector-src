<?php

namespace Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Fixture;

use Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromClassMethodAttribute;

#[RemoveFromClassMethodAttribute]
class RemoveAttributeFromClassOnly
{
    #[RemoveFromClassMethodAttribute]
    private array $property;
    #[RemoveFromClassMethodAttribute]
    private const CONSTANT = 1;
    #[RemoveFromClassMethodAttribute]
    public function __construct(
        #[RemoveFromClassMethodAttribute]
        public array $promotedProperty
    )
    {
    }
    #[RemoveFromClassMethodAttribute]
    public function doSomething()
    {
    }
    #[RemoveFromClassMethodAttribute]
    public function doSomething2(
        #[RemoveFromClassMethodAttribute]
        string $parameter
    )
    {
    }
}

#[RemoveFromClassMethodAttribute]
function doSomething3()
{
}

?>
-----
<?php

namespace Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Fixture;

use Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromClassMethodAttribute;

#[RemoveFromClassMethodAttribute]
class RemoveAttributeFromClassOnly
{
    #[RemoveFromClassMethodAttribute]
    private array $property;
    #[RemoveFromClassMethodAttribute]
    private const CONSTANT = 1;
    public function __construct(
        #[RemoveFromClassMethodAttribute]
        public array $promotedProperty
    )
    {
    }
    public function doSomething()
    {
    }
    public function doSomething2(
        #[RemoveFromClassMethodAttribute]
        string $parameter
    )
    {
    }
}

#[RemoveFromClassMethodAttribute]
function doSomething3()
{
}

?>
