<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterface;
use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterfaceImplementation;

class WithInterfaceAndItsImplementation
{
    private SomeInterface $basic;

    private SomeInterface $withNullableParam;

    private SomeInterface $withUnionWithNullInParam;

    private SomeInterface $withDefaultValue;

    private ?SomeInterface $nullableWithDefaultValue;

    private null|SomeInterface $unionWithNullWithDefaultValue;

    private SomeInterface $withNullableParamWithDefaultValue;

    private SomeInterface $withUnionWithNullInParamWithDefaultValue;

    public function __construct(
        SomeInterfaceImplementation $basic,
        ?SomeInterfaceImplementation $withNullableParam,
        null|SomeInterfaceImplementation $withUnionWithNullInParam,
        SomeInterfaceImplementation $withDefaultValue = new SomeInterfaceImplementation(),
        SomeInterfaceImplementation $nullableWithDefaultValue = null,
        SomeInterfaceImplementation $unionWithNullWithDefaultValue = null,
        ?SomeInterfaceImplementation $withNullableParamWithDefaultValue = null,
        null|SomeInterfaceImplementation $withUnionWithNullInParamWithDefaultValue = null,
    ) {
        $this->basic = $basic;
        $this->withNullableParam = $withNullableParam;
        $this->withUnionWithNullInParam = $withUnionWithNullInParam;
        $this->withDefaultValue = $withDefaultValue;
        $this->nullableWithDefaultValue = $nullableWithDefaultValue;
        $this->unionWithNullWithDefaultValue = $unionWithNullWithDefaultValue;
        $this->withNullableParamWithDefaultValue = $withNullableParamWithDefaultValue;
        $this->withUnionWithNullInParamWithDefaultValue = $withUnionWithNullInParamWithDefaultValue;
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
    public function __construct(private SomeInterfaceImplementation $basic, private ?SomeInterfaceImplementation $withNullableParam, private null|SomeInterfaceImplementation $withUnionWithNullInParam, private SomeInterfaceImplementation $withDefaultValue = new SomeInterfaceImplementation(), private ?\Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterfaceImplementation $nullableWithDefaultValue = null, private ?\Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterfaceImplementation $unionWithNullWithDefaultValue = null, private ?SomeInterfaceImplementation $withNullableParamWithDefaultValue = null, private null|SomeInterfaceImplementation $withUnionWithNullInParamWithDefaultValue = null)
    {
    }
}

?>
