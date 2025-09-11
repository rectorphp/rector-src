<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockGetterReturnArrayFromPropertyDocblockVarRector;

return RectorConfig::configure()
    ->withRules([DocblockGetterReturnArrayFromPropertyDocblockVarRector::class]);
