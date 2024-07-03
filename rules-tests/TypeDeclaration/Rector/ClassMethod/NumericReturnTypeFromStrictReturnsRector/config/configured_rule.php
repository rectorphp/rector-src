<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictReturnsRector;

return RectorConfig::configure()
    ->withRules([NumericReturnTypeFromStrictReturnsRector::class]);
