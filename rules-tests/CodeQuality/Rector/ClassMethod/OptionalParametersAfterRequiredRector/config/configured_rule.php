<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([OptionalParametersAfterRequiredRector::class]);
