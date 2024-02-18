<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Assign\RemoveDoubleAssignRector;

return RectorConfig::configure()
    ->withRules([RemoveDoubleAssignRector::class]);
