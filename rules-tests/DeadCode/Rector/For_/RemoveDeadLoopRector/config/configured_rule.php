<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\For_\RemoveDeadLoopRector;

return RectorConfig::configure()
    ->withRules([RemoveDeadLoopRector::class]);
