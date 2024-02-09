<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\EarlyReturn\Rector\If_\RemoveAlwaysElseRector;
use Rector\EarlyReturn\Rector\StmtsAwareInterface\ReturnEarlyIfVariableRector;

return RectorConfig::configure()->withRules([RemoveAlwaysElseRector::class, ReturnEarlyIfVariableRector::class]);
