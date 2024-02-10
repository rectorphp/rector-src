<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Return_\RemoveDeadConditionAboveReturnRector;

return RectorConfig::configure()
    ->withRules([RemoveDeadConditionAboveReturnRector::class]);
