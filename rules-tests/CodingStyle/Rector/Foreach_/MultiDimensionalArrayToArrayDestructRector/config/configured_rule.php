<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Foreach_\MultiDimensionalArrayToArrayDestructRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([MultiDimensionalArrayToArrayDestructRector::class]);
