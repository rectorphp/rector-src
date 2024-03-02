<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector;
use Rector\DeadCode\Rector\Expression\RemoveDeadStmtRector;

return RectorConfig::configure()
    ->withRules([RemoveDeadStmtRector::class, RemoveUnusedVariableAssignRector::class]);
