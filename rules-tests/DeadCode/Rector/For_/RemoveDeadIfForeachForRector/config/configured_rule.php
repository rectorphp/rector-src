<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\For_\RemoveDeadIfForeachForRector;

return RectorConfig::configure()
    ->withRules([RemoveDeadIfForeachForRector::class]);
