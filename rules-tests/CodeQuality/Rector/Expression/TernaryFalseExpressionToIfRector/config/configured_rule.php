<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Expression\TernaryFalseExpressionToIfRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([TernaryFalseExpressionToIfRector::class]);
