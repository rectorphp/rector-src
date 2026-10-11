<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([ExplicitNullableObjectInstanceRector::class]);
