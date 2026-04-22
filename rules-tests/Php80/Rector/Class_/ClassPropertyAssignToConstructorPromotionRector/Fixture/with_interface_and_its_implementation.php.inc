<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterface;
use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterfaceImplementation;

class WithInterfaceAndItsImplementation
{
    private SomeInterface $basic;

    private SomeInterface $withDefaultValue;

    private ?SomeInterface $nullable;

    private null|SomeInterface $unionWithNull;

    private SomeInterface $withNullableParam;

    public function __construct(
        SomeInterfaceImplementation $basic,
        SomeInterfaceImplementation $withDefaultValue = new SomeInterfaceImplementation(),
        SomeInterfaceImplementation $nullable = null,
        SomeInterfaceImplementation $unionWithNull = null,
        ?SomeInterfaceImplementation $withNullableParam = null,
    ) {
        $this->basic = $basic;
        $this->withDefaultValue = $withDefaultValue;
        $this->nullable = $nullable;
        $this->unionWithNull = $unionWithNull;
        $this->withNullableParam = $withNullableParam;
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
    public function __construct(private SomeInterfaceImplementation $basic, private SomeInterfaceImplementation $withDefaultValue = new SomeInterfaceImplementation(), private ?\Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterfaceImplementation $nullable = null, private ?\Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterfaceImplementation $unionWithNull = null, private ?SomeInterfaceImplementation $withNullableParam = null)
    {
    }
}

?>
