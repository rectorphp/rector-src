<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Assign\CombinedAssignRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([CombinedAssignRector::class]);
