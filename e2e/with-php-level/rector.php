<?php

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersionFeature;

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/src'])
    ->withPhpVersion(PhpVersionFeature::ATTRIBUTES)
    ->withPhpLevel(2);
