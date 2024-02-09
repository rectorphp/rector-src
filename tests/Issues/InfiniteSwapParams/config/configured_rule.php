<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector;
use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Catch_\RemoveUnusedVariableInCatchRector;

return RectorConfig::configure()->withRules(
    [RemoveUnusedVariableInCatchRector::class, OptionalParametersAfterRequiredRector::class]
);
