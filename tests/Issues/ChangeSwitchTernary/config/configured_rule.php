<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Expression\TernaryFalseExpressionToIfRector;
use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Switch_\ChangeSwitchToMatchRector;

return RectorConfig::configure()
    ->withRules([ChangeSwitchToMatchRector::class, TernaryFalseExpressionToIfRector::class]);
