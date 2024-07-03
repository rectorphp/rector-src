<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanConstReturnsRector;

return RectorConfig::configure()
    ->withRules([BoolReturnTypeFromBooleanConstReturnsRector::class]);
