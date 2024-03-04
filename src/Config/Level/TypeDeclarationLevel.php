<?php

declare(strict_types=1);

namespace Rector\Config\Level;

use Rector\Contract\Rector\RectorInterface;
use Rector\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector;
use Rector\TypeDeclaration\Rector\Class_\AddTestsVoidReturnTypeWhereNoReturnRector;
use Rector\TypeDeclaration\Rector\Class_\ChildDoctrineRepositoryClassTypeRector;
use Rector\TypeDeclaration\Rector\Class_\MergeDateTimePropertyTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector;
use Rector\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationBasedOnParentClassMethodRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Rector\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromStrictScalarReturnsRector;
use Rector\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ParamTypeByParentCallTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnDirectArrayRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictBoolReturnExprRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictConstantReturnRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictFluentReturnRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeCallRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedPropertyRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector;
use Rector\TypeDeclaration\Rector\ClassMethod\StrictStringParamConcatRector;
use Rector\TypeDeclaration\Rector\Closure\AddClosureVoidReturnTypeWhereNoReturnRector;
use Rector\TypeDeclaration\Rector\Empty_\EmptyOnNullableObjectToInstanceOfRector;
use Rector\TypeDeclaration\Rector\Function_\AddFunctionVoidReturnTypeWhereNoReturnRector;
use Rector\TypeDeclaration\Rector\FunctionLike\AddParamTypeSplFixedArrayRector;
use Rector\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictSetUpRector;

final class TypeDeclarationLevel
{
    /**
     * The rule order matters, as its used in withTypeCoverageLevel() method
     * Place the safest rules first, follow by more complex ones
     *
     * Use the index to find which rules are applied for each withTypeCoverageLevel() level
     *
     * @var array<class-string<RectorInterface>>
     */
    public const RULES = [
        // php 7.0
        // start with closure first, as safest
        0 => AddClosureVoidReturnTypeWhereNoReturnRector::class,
        1 => AddFunctionVoidReturnTypeWhereNoReturnRector::class,
        2 => AddTestsVoidReturnTypeWhereNoReturnRector::class,

        3 => AddArrowFunctionReturnTypeRector::class,
        4 => ReturnTypeFromStrictConstantReturnRector::class,
        5 => ReturnTypeFromStrictNewArrayRector::class,
        6 => ReturnTypeFromStrictBoolReturnExprRector::class,
        7 => NumericReturnTypeFromStrictScalarReturnsRector::class,
        8 => BoolReturnTypeFromStrictScalarReturnsRector::class,
        9 => ReturnTypeFromStrictTernaryRector::class,
        10 => ReturnTypeFromStrictScalarReturnExprRector::class,
        11 => ReturnTypeFromReturnDirectArrayRector::class,
        12 => ReturnTypeFromReturnNewRector::class,

        13 => AddVoidReturnTypeWhereNoReturnRector::class,

        // php 7.4
        14 => EmptyOnNullableObjectToInstanceOfRector::class,

        // php 7.4
        15 => TypedPropertyFromStrictConstructorRector::class,
        16 => ReturnTypeFromStrictTypedPropertyRector::class,
        17 => AddParamTypeSplFixedArrayRector::class,
        18 => AddReturnTypeDeclarationFromYieldsRector::class,
        19 => AddParamTypeBasedOnPHPUnitDataProviderRector::class,

        // php 7.4
        20 => TypedPropertyFromStrictSetUpRector::class,
        21 => ReturnTypeFromStrictNativeCallRector::class,
        22 => ReturnTypeFromStrictTypedCallRector::class,
        23 => ChildDoctrineRepositoryClassTypeRector::class,

        // param
        24 => AddMethodCallBasedStrictParamTypeRector::class,
        25 => ParamTypeByParentCallTypeRector::class,
        26 => ReturnUnionTypeRector::class,

        // more risky rules
        27 => ReturnTypeFromStrictParamRector::class,
        28 => AddParamTypeFromPropertyTypeRector::class,
        29 => MergeDateTimePropertyTypeDeclarationRector::class,
        30 => PropertyTypeFromStrictSetterGetterRector::class,
        31 => ParamTypeByMethodCallTypeRector::class,
        32 => TypedPropertyFromAssignsRector::class,
        33 => AddReturnTypeDeclarationBasedOnParentClassMethodRector::class,
        34 => ReturnTypeFromStrictFluentReturnRector::class,
        35 => ReturnNeverTypeRector::class,
        36 => StrictArrayParamDimFetchRector::class,
        37 => StrictStringParamConcatRector::class,
    ];
}
