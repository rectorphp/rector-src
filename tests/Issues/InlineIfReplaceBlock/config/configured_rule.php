<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Expression\InlineIfToExplicitIfRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Block\ReplaceBlockToItsStmtsRector;

return RectorConfig::configure()
    ->withRules([InlineIfToExplicitIfRector::class, ReplaceBlockToItsStmtsRector::class]);
