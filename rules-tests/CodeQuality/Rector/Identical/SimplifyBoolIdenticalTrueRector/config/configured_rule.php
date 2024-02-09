<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Identical\SimplifyBoolIdenticalTrueRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([SimplifyBoolIdenticalTrueRector::class]);
