<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Stmt\RemoveEmptyArrayConditionReturnRector;

return RectorConfig::configure()
    ->withRules([RemoveEmptyArrayConditionReturnRector::class]);
