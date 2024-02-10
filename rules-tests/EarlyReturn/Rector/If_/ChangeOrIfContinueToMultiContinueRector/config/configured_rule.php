<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\EarlyReturn\Rector\If_\ChangeOrIfContinueToMultiContinueRector;

return RectorConfig::configure()
    ->withRules([ChangeOrIfContinueToMultiContinueRector::class]);
