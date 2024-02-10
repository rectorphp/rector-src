<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Ternary\NumberCompareToMaxFuncCallRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([NumberCompareToMaxFuncCallRector::class]);
