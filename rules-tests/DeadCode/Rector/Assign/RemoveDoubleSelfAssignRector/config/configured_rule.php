<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Assign\RemoveDoubleSelfAssignRector;

return RectorConfig::configure()
    ->withRules([RemoveDoubleSelfAssignRector::class]);
