<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\SimplifyIfNotNullReturnRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([SimplifyIfNotNullReturnRector::class]);
