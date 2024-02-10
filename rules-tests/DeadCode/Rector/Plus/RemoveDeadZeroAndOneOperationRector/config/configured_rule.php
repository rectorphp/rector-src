<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector;

return RectorConfig::configure()
    ->withRules([RemoveDeadZeroAndOneOperationRector::class]);
