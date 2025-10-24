<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\If_\RemoveDeadIfBlockRector;

return RectorConfig::configure()
    ->withRules([RemoveDeadIfBlockRector::class]);
