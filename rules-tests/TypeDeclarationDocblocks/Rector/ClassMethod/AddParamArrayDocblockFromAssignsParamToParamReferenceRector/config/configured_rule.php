<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromAssignsParamToParamReferenceRector;

return RectorConfig::configure()
    ->withRules([AddParamArrayDocblockFromAssignsParamToParamReferenceRector::class]);
