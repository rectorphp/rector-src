<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\CodeQuality\Rector\If_\ObjectExplicitBoolCompareRector;
use Rector\Config\RectorConfig;
use Rector\EarlyReturn\Rector\Return_\ReturnBinaryOrToEarlyReturnRector;

return RectorConfig::configure()
    ->withRules(
        [
            ObjectExplicitBoolCompareRector::class,
            FlipTypeControlToUseExclusiveTypeRector::class,
            ReturnBinaryOrToEarlyReturnRector::class,
        ]
    );
