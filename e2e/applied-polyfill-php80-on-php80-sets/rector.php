<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/src'])
    ->withPhpVersion(PhpVersion::PHP_74) // using php 7.4
    ->withPhpSets(php80: true); // enable php 8.0 set, it will be applied as there is polyfill
