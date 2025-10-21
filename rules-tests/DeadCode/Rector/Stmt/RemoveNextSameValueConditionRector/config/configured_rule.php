<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Stmt\RemoveNextSameValueConditionRector;

return RectorConfig::configure()
    ->withRules([RemoveNextSameValueConditionRector::class]);
