<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Empty_\ReplaceUnnecessaryEmptyCallsWithNativeExpressionsRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([ReplaceUnnecessaryEmptyCallsWithNativeExpressionsRector::class]);
