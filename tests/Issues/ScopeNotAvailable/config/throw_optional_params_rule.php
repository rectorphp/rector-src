<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector;
use Rector\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([ThrowWithPreviousExceptionRector::class, OptionalParametersAfterRequiredRector::class]);
