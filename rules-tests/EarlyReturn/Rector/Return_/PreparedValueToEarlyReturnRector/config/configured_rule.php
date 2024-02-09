<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\EarlyReturn\Rector\Return_\PreparedValueToEarlyReturnRector;

return RectorConfig::configure()->withRules([PreparedValueToEarlyReturnRector::class]);
