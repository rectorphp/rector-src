<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPhpSets()
    ->withPaths([__DIR__])
    ->withPHPStanConfigs([__DIR__ . '/phpstan.neon']);
