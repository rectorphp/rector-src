<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Ternary\SimplifyTautologyTernaryRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([SimplifyTautologyTernaryRector::class]);
