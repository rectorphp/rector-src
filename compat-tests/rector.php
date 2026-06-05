<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\RectorCompatTests\Rector\UseGetArgRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/fixture',
    ])
    ->withRules([UseGetArgRector::class])
    ->withTypeCoverageLevel(0)
    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(0);
