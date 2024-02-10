<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\LogicalAnd\LogicalToBooleanRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([LogicalToBooleanRector::class]);
