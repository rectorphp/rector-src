<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\EarlyReturn\Rector\If_\ChangeAndIfToEarlyReturnRector;

return RectorConfig::configure()
    ->withRules([ChangeAndIfToEarlyReturnRector::class]);
