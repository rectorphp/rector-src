<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\EarlyReturn\Rector\Foreach_\ChangeNestedForeachIfsToEarlyContinueRector;

return RectorConfig::configure()
    ->withRules([ChangeNestedForeachIfsToEarlyContinueRector::class]);
