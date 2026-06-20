<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclarationDocblocks\Rector\Class_\AddParamTypeToRefactorMethodRector;

return RectorConfig::configure()
    ->withRules([AddParamTypeToRefactorMethodRector::class])
    ->withImportNames();
