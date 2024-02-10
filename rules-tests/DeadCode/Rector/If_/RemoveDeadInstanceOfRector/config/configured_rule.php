<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\If_\RemoveDeadInstanceOfRector;

return RectorConfig::configure()
    ->withRules([RemoveDeadInstanceOfRector::class]);
