<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictBoolReturnExprRector;

return RectorConfig::configure()
    ->withRules([ReturnTypeFromStrictBoolReturnExprRector::class]);
