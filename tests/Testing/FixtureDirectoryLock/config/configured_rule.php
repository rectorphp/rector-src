<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Function_\AddFunctionVoidReturnTypeWhereNoReturnRector;

return RectorConfig::configure()
    ->withRules([AddFunctionVoidReturnTypeWhereNoReturnRector::class]);
