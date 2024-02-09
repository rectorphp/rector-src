<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\NotEqual\CommonNotEqualRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([CommonNotEqualRector::class]);
