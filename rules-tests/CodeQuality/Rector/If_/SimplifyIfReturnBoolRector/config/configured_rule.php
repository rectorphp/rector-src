<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([SimplifyIfReturnBoolRector::class]);
