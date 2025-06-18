<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Ternary\TernaryImplodeToImplodeRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([TernaryImplodeToImplodeRector::class]);
