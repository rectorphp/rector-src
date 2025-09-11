<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Class_\DocblockVarFromParamDocblockInConstructorRector;
use Rector\TypeDeclaration\Rector\ClassMethod\DocblockGetterReturnArrayFromPropertyDocblockVarRector;

return static function (RectorConfig $rectorConfig): void {
    // 2025-09, experimental hidden set for type declaration in docblocks

    $rectorConfig->rules([
        // properties
        DocblockVarFromParamDocblockInConstructorRector::class,

        DocblockGetterReturnArrayFromPropertyDocblockVarRector::class,
    ]);
};
