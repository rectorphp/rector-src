<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php54\Rector\Break_\RemoveZeroBreakContinueRector;

return RectorConfig::configure()
    ->withRules([RemoveZeroBreakContinueRector::class]);
