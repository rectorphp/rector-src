<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\EarlyReturn\Rector\If_\ChangeNestedIfsToEarlyReturnRector;

return RectorConfig::configure()
    ->withRules([ChangeNestedIfsToEarlyReturnRector::class]);
