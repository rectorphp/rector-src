<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromDataProviderRector;

return RectorConfig::configure()
    ->withRules([AddParamArrayDocblockFromDataProviderRector::class]);
