<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector;

return RectorConfig::configure()->withRules([RemoveUnreachableStatementRector::class]);
