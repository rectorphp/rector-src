<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Foreach_\SimplifyForeachToCoalescingRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([SimplifyForeachToCoalescingRector::class]);
