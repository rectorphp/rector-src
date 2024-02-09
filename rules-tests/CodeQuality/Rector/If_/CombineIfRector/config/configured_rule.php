<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\CombineIfRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([CombineIfRector::class]);
