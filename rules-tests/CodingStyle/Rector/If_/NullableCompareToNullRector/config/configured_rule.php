<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\If_\NullableCompareToNullRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([NullableCompareToNullRector::class]);
