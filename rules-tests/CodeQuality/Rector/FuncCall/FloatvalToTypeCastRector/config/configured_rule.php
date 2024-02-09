<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\FloatvalToTypeCastRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([FloatvalToTypeCastRector::class]);
