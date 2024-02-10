<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Include_\AbsolutizeRequireAndIncludePathRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([AbsolutizeRequireAndIncludePathRector::class]);
