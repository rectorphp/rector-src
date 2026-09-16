<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanConstAndStrictReturnsRector;

return RectorConfig::configure()
    ->withRules([BoolReturnTypeFromBooleanConstAndStrictReturnsRector::class]);
