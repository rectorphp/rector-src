<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ParamAnnotationIncorrectNullableRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ParamTypeByParentCallTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector;
use Rector\TypeDeclaration\Rector\FunctionLike\AddParamTypeSplFixedArrayRector;
use Rector\TypeDeclaration\Rector\Param\ParamTypeFromStrictTypedPropertyRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        ParamTypeByMethodCallTypeRector::class,
        ParamAnnotationIncorrectNullableRector::class,
        ParamTypeFromStrictTypedPropertyRector::class,
        AddMethodCallBasedStrictParamTypeRector::class,
        ParamTypeByParentCallTypeRector::class,
        AddParamTypeSplFixedArrayRector::class,
        AddParamTypeBasedOnPHPUnitDataProviderRector::class,
        AddParamTypeFromPropertyTypeRector::class,
        StrictArrayParamDimFetchRector::class,
    ]);
};
