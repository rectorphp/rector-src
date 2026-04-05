<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Coalesce\CoalesceToTernaryRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([CoalesceToTernaryRector::class]);
