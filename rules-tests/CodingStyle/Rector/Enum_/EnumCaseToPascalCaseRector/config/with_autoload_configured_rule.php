<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([EnumCaseToPascalCaseRector::class])
    ->withAutoloadPaths([
        __DIR__ . '/../Source'
    ]);
