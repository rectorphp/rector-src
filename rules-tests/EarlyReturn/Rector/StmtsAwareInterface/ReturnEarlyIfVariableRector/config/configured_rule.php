<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\EarlyReturn\Rector\StmtsAwareInterface\ReturnEarlyIfVariableRector;

return RectorConfig::configure()
    ->withRules([ReturnEarlyIfVariableRector::class]);
