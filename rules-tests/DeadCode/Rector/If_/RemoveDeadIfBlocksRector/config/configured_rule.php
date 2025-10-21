<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\If_\RemoveDeadIfBlocksRector;

return RectorConfig::configure()
    ->withRules([RemoveDeadIfBlocksRector::class]);
