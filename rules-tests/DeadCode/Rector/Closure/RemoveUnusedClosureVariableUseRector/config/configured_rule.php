<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Closure\RemoveUnusedClosureVariableUseRector;

return RectorConfig::configure()
    ->withRules([RemoveUnusedClosureVariableUseRector::class]);
