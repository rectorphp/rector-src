<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Identical\SimplifyConditionsRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([SimplifyConditionsRector::class]);
