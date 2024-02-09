<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([UseIdenticalOverEqualWithSameTypeRector::class]);
