<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\StringReturnTypeFromStrictScalarReturnsRector;

return RectorConfig::configure()
    ->withRules([StringReturnTypeFromStrictScalarReturnsRector::class]);
