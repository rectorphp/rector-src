<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php71\Rector\Foreach\MultiDimensionalArrayToArrayDestructRector;

return RectorConfig::configure()
    ->withRules([MultiDimensionalArrayToArrayDestructRector::class]);
