<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\DocblockGetterReturnArrayFromPropertyDocblockVarRector;

return RectorConfig::configure()
    ->withRules([DocblockGetterReturnArrayFromPropertyDocblockVarRector::class]);
