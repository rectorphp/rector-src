<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([ExplicitBoolCompareRector::class]);
