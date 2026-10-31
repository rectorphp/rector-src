<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector;

return RectorConfig::configure()
    ->withRules([ExplicitNullableObjectInstanceRector::class, RemoveAlwaysTrueIfConditionRector::class])
    ->withImportNames();
