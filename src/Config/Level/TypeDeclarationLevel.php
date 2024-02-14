<?php

declare(strict_types=1);

namespace Rector\Config\Level;

use Rector\Contract\Rector\RectorInterface;
use Rector\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector;
use Rector\TypeDeclaration\Rector\Class_\AddTestsVoidReturnTypeWhereNoReturnRector;
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
     * @var array<class-string<RectorInterface>>
     */
    public const RULES = [
        // php 7.0
        // start with closure first, as safest
        AddClosureVoidReturnTypeWhereNoReturnRector::class,
        AddFunctionVoidReturnTypeWhereNoReturnRector::class,
        AddTestsVoidReturnTypeWhereNoReturnRector::class,

        AddVoidReturnTypeWhereNoReturnRector::class,

        // php 7.4
        AddArrowFunctionReturnTypeRector::class,
        ReturnTypeFromStrictNewArrayRector::class,
        ReturnTypeFromStrictConstantReturnRector::class,
        NumericReturnTypeFromStrictScalarReturnsRector::class,
        ReturnTypeFromStrictScalarReturnExprRector::class,
        ReturnTypeFromStrictBoolReturnExprRector::class,
        ReturnTypeFromStrictTernaryRector::class,
        EmptyOnNullableObjectToInstanceOfRector::class,

        // php 7.4
        TypedPropertyFromStrictConstructorRector::class,
        ReturnTypeFromReturnDirectArrayRector::class,
        ReturnTypeFromStrictTypedPropertyRector::class,
        AddParamTypeSplFixedArrayRector::class,
        AddReturnTypeDeclarationFromYieldsRector::class,
        AddParamTypeBasedOnPHPUnitDataProviderRector::class,

        // php 7.4
        TypedPropertyFromStrictSetUpRector::class,
        ReturnTypeFromReturnNewRector::class,
        BoolReturnTypeFromStrictScalarReturnsRector::class,
        ReturnTypeFromStrictNativeCallRector::class,
        ReturnTypeFromStrictTypedCallRector::class,

        // param
        AddMethodCallBasedStrictParamTypeRector::class,
        ParamTypeByParentCallTypeRector::class,
        ReturnUnionTypeRector::class,

        // more risky rules
        ReturnTypeFromStrictParamRector::class,
        AddParamTypeFromPropertyTypeRector::class,
        MergeDateTimePropertyTypeDeclarationRector::class,
        PropertyTypeFromStrictSetterGetterRector::class,
        ParamTypeByMethodCallTypeRector::class,
        TypedPropertyFromAssignsRector::class,
        AddReturnTypeDeclarationBasedOnParentClassMethodRector::class,
        ReturnTypeFromStrictFluentReturnRector::class,
        ReturnNeverTypeRector::class,
        StrictArrayParamDimFetchRector::class,
        StrictStringParamConcatRector::class,
    ];
}
