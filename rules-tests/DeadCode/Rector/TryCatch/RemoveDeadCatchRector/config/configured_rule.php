<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\TryCatch\RemoveDeadCatchRector;

return RectorConfig::configure()
    ->withRules([RemoveDeadCatchRector::class]);
