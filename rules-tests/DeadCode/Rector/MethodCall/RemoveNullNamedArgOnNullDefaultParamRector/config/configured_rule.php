<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\MethodCall\RemoveNullNamedArgOnNullDefaultParamRector;

return RectorConfig::configure()
    ->withRules([RemoveNullNamedArgOnNullDefaultParamRector::class]);
