<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/src'])
    // the PHPUnit set puts PHPStan types in the configuration, which the cache hashes
    ->withComposerBased(phpunit: true)
    ->withParallel()
    ->withCache(__DIR__ . '/cache');
