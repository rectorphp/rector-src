<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\ArrayExplicitBoolCompareRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([ArrayExplicitBoolCompareRector::class]);
