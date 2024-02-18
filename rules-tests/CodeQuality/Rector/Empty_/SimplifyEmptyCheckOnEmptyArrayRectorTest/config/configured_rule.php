<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([SimplifyEmptyCheckOnEmptyArrayRector::class]);
