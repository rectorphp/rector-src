<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterface;
use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterfaceImplementation;

class WithInterfaceAndItsImplementation
{
    private SomeInterface $x;

    private SomeInterface $y;

    private ?SomeInterface $z;

    public function __construct(
        SomeInterfaceImplementation $x,
        SomeInterfaceImplementation $y = new SomeInterfaceImplementation(),
        SomeInterfaceImplementation $z = null,
    ) {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterface;
use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterfaceImplementation;

class WithInterfaceAndItsImplementation
{
    public function __construct(private SomeInterfaceImplementation $x, private SomeInterfaceImplementation $y = new SomeInterfaceImplementation(), private ?SomeInterfaceImplementation $z = null)
    {
    }
}

?>
