<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector;

return RectorConfig::configure()
    ->withRules([RemoveNullArgOnNullDefaultParamRector::class]);
