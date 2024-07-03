<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanStrictReturnsRector;

return RectorConfig::configure()
    ->withRules([BoolReturnTypeFromBooleanStrictReturnsRector::class]);
