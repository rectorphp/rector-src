<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterface;
use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterfaceImplementation;

class WithInterfaceAndItsImplementation
{
    private SomeInterface $basic;

    private SomeInterface $withNullableParam;

    private SomeInterface $withUnionAndNullInParam;

    private SomeInterface $withDefaultValue;

    private ?SomeInterface $withNonNullableParamAndDefaultValueNull;

    private null|SomeInterface $unionWithNullAndDefaultValue;

    private SomeInterface $withNullableParamAndDefaultValue;

    private SomeInterface $withUnionAndNullInParamAndDefaultValue;

    public function __construct(
        SomeInterfaceImplementation $basic,
        ?SomeInterfaceImplementation $withNullableParam,
        null|SomeInterfaceImplementation $withUnionAndNullInParam,
        SomeInterfaceImplementation $withDefaultValue = new SomeInterfaceImplementation(),
        SomeInterfaceImplementation $withNonNullableParamAndDefaultValueNull = null,
        SomeInterfaceImplementation $unionWithNullAndDefaultValue = null,
        ?SomeInterfaceImplementation $withNullableParamAndDefaultValue = null,
        null|SomeInterfaceImplementation $withUnionAndNullInParamAndDefaultValue = null,
    ) {
        $this->basic = $basic;
        $this->withNullableParam = $withNullableParam;
        $this->withUnionAndNullInParam = $withUnionAndNullInParam;
        $this->withDefaultValue = $withDefaultValue;
        $this->withNonNullableParamAndDefaultValueNull = $withNonNullableParamAndDefaultValueNull;
        $this->unionWithNullAndDefaultValue = $unionWithNullAndDefaultValue;
        $this->withNullableParamAndDefaultValue = $withNullableParamAndDefaultValue;
        $this->withUnionAndNullInParamAndDefaultValue = $withUnionAndNullInParamAndDefaultValue;
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
    public function __construct(private SomeInterfaceImplementation $basic, private ?SomeInterfaceImplementation $withNullableParam, private null|SomeInterfaceImplementation $withUnionAndNullInParam, private SomeInterfaceImplementation $withDefaultValue = new SomeInterfaceImplementation(), private ?\Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterfaceImplementation $withNonNullableParamAndDefaultValueNull = null, private ?\Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterfaceImplementation $unionWithNullAndDefaultValue = null, private ?SomeInterfaceImplementation $withNullableParamAndDefaultValue = null, private null|SomeInterfaceImplementation $withUnionAndNullInParamAndDefaultValue = null)
    {
    }
}

?>
