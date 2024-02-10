<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\BooleanNot\SimplifyDeMorganBinaryRector;
use Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector;
use Rector\Config\RectorConfig;
use Rector\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector;

return RectorConfig::configure()
    ->withRules(
        [
            ChangeAndIfToEarlyReturnRector::class,
            CompleteDynamicPropertiesRector::class,
            SimplifyDeMorganBinaryRector::class,
        ]
    );
