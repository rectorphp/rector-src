<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\FuncCall\AddArrowFunctionParamArrayWhereDimFetchRector;

return RectorConfig::configure()
    ->withRules([AddArrowFunctionParamArrayWhereDimFetchRector::class]);
