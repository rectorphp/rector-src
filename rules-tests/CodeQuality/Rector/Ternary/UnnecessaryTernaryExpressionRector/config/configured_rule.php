<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Ternary\UnnecessaryTernaryExpressionRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([UnnecessaryTernaryExpressionRector::class]);
