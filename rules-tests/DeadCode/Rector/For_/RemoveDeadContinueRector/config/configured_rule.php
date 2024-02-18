<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\For_\RemoveDeadContinueRector;

return RectorConfig::configure()
    ->withRules([RemoveDeadContinueRector::class]);
