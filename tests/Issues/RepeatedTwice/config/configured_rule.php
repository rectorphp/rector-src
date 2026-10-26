<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector;

return RectorConfig::configure()
    // registered in reverse order of dependency:
    // 2nd pass: native return type from the now-typed param
    // 1st pass: native param type from the typed property
    ->withRules([
        ReturnTypeFromStrictParamRector::class,
        AddParamTypeFromPropertyTypeRector::class,
    ]);
