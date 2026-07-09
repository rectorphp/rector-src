<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\ObjectExplicitBoolCompareRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([ObjectExplicitBoolCompareRector::class]);
