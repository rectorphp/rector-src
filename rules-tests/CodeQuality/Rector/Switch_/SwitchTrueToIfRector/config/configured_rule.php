<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Switch_\SwitchTrueToIfRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([SwitchTrueToIfRector::class]);
