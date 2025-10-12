<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Stmt\RemoveConditionExactReturnRector;

return RectorConfig::configure()
    ->withRules([RemoveConditionExactReturnRector::class]);
