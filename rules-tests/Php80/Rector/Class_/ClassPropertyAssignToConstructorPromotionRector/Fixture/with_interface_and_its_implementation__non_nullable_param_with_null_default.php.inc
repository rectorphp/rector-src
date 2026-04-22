<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterface;
use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterfaceImplementation;

class WithInterfaceAndItsImplementationNonNullableParamWithNullDefault
{
    private SomeInterface $x;

    public function __construct(
        SomeInterfaceImplementation $x = null,
    ) {
        $this->x = $x;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterface;
use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterfaceImplementation;

class WithInterfaceAndItsImplementationNonNullableParamWithNullDefault
{
    public function __construct(private ?\Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterfaceImplementation $x = null)
    {
    }
}

?>
