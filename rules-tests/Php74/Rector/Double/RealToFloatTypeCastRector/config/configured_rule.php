<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php74\Rector\Double\RealToFloatTypeCastRector;

return RectorConfig::configure()
    ->withRules([RealToFloatTypeCastRector::class]);
