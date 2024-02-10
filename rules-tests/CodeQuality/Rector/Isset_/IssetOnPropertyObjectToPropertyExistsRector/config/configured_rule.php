<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([IssetOnPropertyObjectToPropertyExistsRector::class]);
