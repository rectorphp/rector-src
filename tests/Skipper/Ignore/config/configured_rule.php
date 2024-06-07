<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Rector\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector;

return RectorConfig::configure()
    ->withRules([
        ClassPropertyAssignToConstructorPromotionRector::class,
        AddParamTypeFromPropertyTypeRector::class,
        AddVoidReturnTypeWhereNoReturnRector::class,
        NumericReturnTypeFromStrictScalarReturnsRector::class,
    ]);
