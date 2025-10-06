<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockBasedOnArrayMapRector;

return RectorConfig::configure()
    ->withRules([AddParamArrayDocblockBasedOnArrayMapRector::class]);
