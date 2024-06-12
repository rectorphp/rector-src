<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Cast\RecastingRemovalRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Rector\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector;

return RectorConfig::configure()
    ->withRules([
        AddParamTypeFromPropertyTypeRector::class,
        AddVoidReturnTypeWhereNoReturnRector::class,
        NumericReturnTypeFromStrictScalarReturnsRector::class,
        RecastingRemovalRector::class
    ]);
