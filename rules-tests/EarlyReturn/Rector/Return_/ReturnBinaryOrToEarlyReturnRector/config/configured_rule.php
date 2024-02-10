<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\EarlyReturn\Rector\Return_\ReturnBinaryOrToEarlyReturnRector;

return RectorConfig::configure()
    ->withRules([ReturnBinaryOrToEarlyReturnRector::class]);
