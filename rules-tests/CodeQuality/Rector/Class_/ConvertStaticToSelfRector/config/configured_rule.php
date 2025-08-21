<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\ConvertStaticToSelfRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([ConvertStaticToSelfRector::class]);
