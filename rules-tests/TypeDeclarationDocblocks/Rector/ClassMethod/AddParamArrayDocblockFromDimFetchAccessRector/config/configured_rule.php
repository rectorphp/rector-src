<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromDimFetchAccessRector;

return RectorConfig::configure()
    ->withRules([AddParamArrayDocblockFromDimFetchAccessRector::class]);
