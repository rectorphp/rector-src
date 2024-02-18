<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([CompleteDynamicPropertiesRector::class]);
