<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterface;
use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterfaceImplementation;

class WithInterfaceAndItsImplementationUnionWithNullInParamAndNonNullableProperty
{
    private SomeInterface $x;

    public function __construct(
        null|SomeInterfaceImplementation $x,
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

class WithInterfaceAndItsImplementationUnionWithNullInParamAndNonNullableProperty
{
    public function __construct(private null|SomeInterfaceImplementation $x)
    {
    }
}

?>
