<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Expression\InlineIfToExplicitIfRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([InlineIfToExplicitIfRector::class]);
