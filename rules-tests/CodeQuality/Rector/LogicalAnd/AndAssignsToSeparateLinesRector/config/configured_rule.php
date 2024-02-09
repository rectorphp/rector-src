<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\LogicalAnd\AndAssignsToSeparateLinesRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([AndAssignsToSeparateLinesRector::class]);
