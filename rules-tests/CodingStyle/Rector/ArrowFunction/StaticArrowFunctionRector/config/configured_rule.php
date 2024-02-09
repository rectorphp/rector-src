<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([StaticArrowFunctionRector::class]);
