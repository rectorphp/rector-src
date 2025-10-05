<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Concat\RemoveUnusedClosureVariableUseRector;

return RectorConfig::configure()
    ->withRules([RemoveUnusedClosureVariableUseRector::class]);
