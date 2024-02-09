<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Assign\RemoveDoubleAssignRector;
use Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector;

return RectorConfig::configure()->withRules([RemoveDoubleAssignRector::class, RemoveUnusedVariableAssignRector::class]);
