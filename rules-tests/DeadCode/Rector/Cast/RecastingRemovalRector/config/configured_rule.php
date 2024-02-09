<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Cast\RecastingRemovalRector;

return RectorConfig::configure()->withRules([RecastingRemovalRector::class]);
