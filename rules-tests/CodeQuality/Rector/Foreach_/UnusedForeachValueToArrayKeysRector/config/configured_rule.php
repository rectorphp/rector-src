<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Foreach_\UnusedForeachValueToArrayKeysRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([UnusedForeachValueToArrayKeysRector::class]);
