<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\SimplifyStrposLowerRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([SimplifyStrposLowerRector::class]);
