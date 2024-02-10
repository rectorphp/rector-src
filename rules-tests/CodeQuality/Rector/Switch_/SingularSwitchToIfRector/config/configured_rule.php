<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Switch_\SingularSwitchToIfRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([SingularSwitchToIfRector::class]);
