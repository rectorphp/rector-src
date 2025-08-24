<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\ConvertStaticToSelfRector;
use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersionFeature;

return RectorConfig::configure()
    ->withRules([ConvertStaticToSelfRector::class])
    ->withPhpVersion(PhpVersionFeature::FINAL_CLASS_CONSTANTS);
