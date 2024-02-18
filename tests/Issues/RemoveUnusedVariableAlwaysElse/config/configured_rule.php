<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector;
use Rector\EarlyReturn\Rector\If_\RemoveAlwaysElseRector;

return RectorConfig::configure()
    ->withRules([RemoveUnusedVariableAssignRector::class, RemoveAlwaysElseRector::class]);
